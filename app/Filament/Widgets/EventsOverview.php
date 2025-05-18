<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Filament\Resources\EventResource;

class EventsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalEvents = Event::count();
        $upcomingEvents = Event::where('start_date', '>=', now())->count();
        $pastEvents = Event::where('end_date', '<', now())->count();

        return [
            Stat::make('Total Events', $totalEvents)
                ->description('All events in the system')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('primary')
                ->url(EventResource::getUrl('index')),
            Stat::make('Upcoming Events', $upcomingEvents)
                ->description('Events yet to happen')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color('success'),
            Stat::make('Past Events', $pastEvents)
                ->description('Already concluded')
                ->descriptionIcon('heroicon-o-arrow-trending-down')
                ->color('danger'),
        ];
    }
}
