<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class SwaggerDocs extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-code-bracket';

    protected static string $view = 'filament.pages.swagger-docs';

    protected static ?string $navigationLabel = 'API Docs';
}
