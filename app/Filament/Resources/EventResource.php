<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers;
use App\Filament\Resources\EventResource\RelationManagers\CategoriesRelationManager;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Content Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->maxLength(65535),
                Forms\Components\Section::make('Location Details')
                    ->schema([
                        Forms\Components\Select::make('location')
                            ->required()
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search) {
                                if (strlen($search) < 3) {
                                    return [];
                                }

                                try {
                                    $response = Http::withHeaders([
                                        'User-Agent' => 'LaravelApp/1.0'
                                    ])->get('https://nominatim.openstreetmap.org/search', [
                                        'q' => $search,
                                        'format' => 'json',
                                        'limit' => 5,
                                        'countrycodes' => 'be',
                                        'addressdetails' => 1
                                    ]);

                                    if ($response->successful()) {
                                        $results = $response->json();
                                        $options = [];

                                        foreach ($results as $result) {
                                            $display = $result['display_name'];
                                            $options[$display] = $display;
                                        }

                                        return $options;
                                    }
                                } catch (\Exception $e) {
                                    Log::error('Autocomplete error: ' . $e->getMessage());
                                }

                                return [];
                            })
                            ->getOptionLabelUsing(fn($value): ?string => $value)
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (empty($state)) {
                                    $set('latitude', null);
                                    $set('longitude', null);
                                    return;
                                }

                                try {
                                    $response = Http::withHeaders([
                                        'User-Agent' => 'LaravelApp/1.0'
                                    ])->get('https://nominatim.openstreetmap.org/search', [
                                        'q' => $state,
                                        'format' => 'json',
                                        'limit' => 1,
                                        'countrycodes' => 'be'
                                    ]);

                                    if ($response->successful()) {
                                        $results = $response->json();

                                        if (!empty($results)) {
                                            $firstResult = $results[0];
                                            $lat = $firstResult['lat'];
                                            $lon = $firstResult['lon'];

                                            $set('latitude', $lat);
                                            $set('longitude', $lon);

                                            Notification::make()
                                                ->title('Location Found')
                                                ->body("Coordinates found for location")
                                                ->success()
                                                ->send();
                                        }
                                    }
                                } catch (\Exception $e) {
                                    Log::error('Location geocoding error: ' . $e->getMessage());
                                }
                            })
                            ->id('location-autocomplete-input')
                            ->placeholder('Start typing an address in Belgium...')
                            ->extraAttributes([
                                'data-country' => 'belgium',
                            ])
                            ->suffixAction(
                                Action::make('geocode')
                                    ->icon('heroicon-o-map-pin')
                                    ->tooltip('Find coordinates')
                                    ->action(function ($set, $get) {
                                        $location = $get('location');
                                        if (!$location) {
                                            Notification::make()
                                                ->title('Missing Location')
                                                ->body('Please enter a location first')
                                                ->warning()
                                                ->send();
                                            return;
                                        }

                                        try {
                                            // Log the geocoding attempt
                                            Log::info('Geocoding address: ' . $location);

                                            // Use OpenStreetMap Nominatim service - more reliable than the package
                                            $response = Http::withHeaders([
                                                'User-Agent' => 'LaravelApp/1.0'  // Required by Nominatim ToS
                                            ])->get('https://nominatim.openstreetmap.org/search', [
                                                'q' => $location . ', Belgium', // Always add Belgium context
                                                'format' => 'json',
                                                'limit' => 1,
                                                'countrycodes' => 'be'
                                            ]);

                                            // Check if we got a valid response
                                            if ($response->successful()) {
                                                $results = $response->json();

                                                if (!empty($results)) {
                                                    // Success - we found coordinates
                                                    $firstResult = $results[0];
                                                    $lat = $firstResult['lat'];
                                                    $lon = $firstResult['lon'];

                                                    // Update the form fields
                                                    $set('latitude', $lat);
                                                    $set('longitude', $lon);

                                                    // Show success notification
                                                    Notification::make()
                                                        ->title('Location Found')
                                                        ->body("Coordinates found: {$lat}, {$lon}")
                                                        ->success()
                                                        ->send();

                                                    return;
                                                }
                                            }

                                            // If we're here, nothing was found with the first attempt
                                            // Try a direct geocoding with possibly more specific parameters
                                            Log::info('First geocoding attempt failed, trying with structured data');

                                            // Try to parse street and city
                                            $parts = explode(',', $location);
                                            $street = trim($parts[0]);
                                            $city = count($parts) > 1 ? trim($parts[1]) : 'Antwerp'; // Default to Antwerp

                                            $response = Http::withHeaders([
                                                'User-Agent' => 'LaravelApp/1.0'
                                            ])->get('https://nominatim.openstreetmap.org/search', [
                                                'street' => $street,
                                                'city' => $city,
                                                'country' => 'Belgium',
                                                'format' => 'json',
                                                'limit' => 1
                                            ]);

                                            if ($response->successful()) {
                                                $results = $response->json();

                                                if (!empty($results)) {
                                                    $firstResult = $results[0];
                                                    $lat = $firstResult['lat'];
                                                    $lon = $firstResult['lon'];

                                                    $set('latitude', $lat);
                                                    $set('longitude', $lon);

                                                    Notification::make()
                                                        ->title('Location Found')
                                                        ->body("Coordinates found with second attempt")
                                                        ->success()
                                                        ->send();

                                                    return;
                                                }
                                            }

                                            // If we get here, we couldn't find coordinates
                                            Notification::make()
                                                ->title('Location Not Found')
                                                ->body("Please try a more specific address (e.g. 'Street, City') or pick a location on the map")
                                                ->warning()
                                                ->send();
                                        } catch (\Exception $e) {
                                            // Log the error
                                            Log::error('Geocoding error: ' . $e->getMessage());

                                            Notification::make()
                                                ->title('Geocoding Error')
                                                ->body('Error: ' . $e->getMessage())
                                                ->danger()
                                                ->send();
                                        }
                                    })
                            ),
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('latitude')
                                    ->label('Latitude')
                                    ->numeric()
                                    ->step(0.0000001)
                                    ->reactive(),
                                Forms\Components\TextInput::make('longitude')
                                    ->label('Longitude')
                                    ->numeric()
                                    ->step(0.0000001)
                                    ->reactive(),
                            ])->columns(2),
                    ]),
                Forms\Components\DateTimePicker::make('start_date')
                    ->required(),
                Forms\Components\DateTimePicker::make('end_date')
                    ->required(),
                Forms\Components\TextInput::make('organizer')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('categories')
                    ->multiple()
                    ->relationship('categories', 'name')
                    ->preload(),
            ]);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('location')
                    ->searchable(),
                Tables\Columns\TextColumn::make('coordinates')
                    ->label('Coordinates')
                    ->formatStateUsing(
                        fn($record) =>
                        $record->latitude && $record->longitude
                            ? "{$record->latitude}, {$record->longitude}"
                            : '—'
                    )
                    ->tooltip(
                        fn($record) =>
                        $record->latitude && $record->longitude
                            ? "Lat: {$record->latitude}, Lng: {$record->longitude}"
                            : 'No coordinates available'
                    ),
                Tables\Columns\TextColumn::make('start_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('organizer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CategoriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
