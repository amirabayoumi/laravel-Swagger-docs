<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Event;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Filament\Resources\CategoryResource;

class CategoriesOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalCategories = Category::count();

        // Get the top category with the most events
        $topCategory = Category::withCount('events')
            ->orderBy('events_count', 'desc')
            ->first();

        $topCategoryName = $topCategory ? $topCategory->name : 'None';
        $topCategoryCount = $topCategory ? $topCategory->events_count : 0;

        // Get average events per category
        $avgEventsPerCategory = Category::has('events')->count() > 0
            ? number_format(Event::count() / Category::has('events')->count(), 1)
            : 0;

        return [
            Stat::make('Total Categories', $totalCategories)
                ->description('All categories in the system')
                ->descriptionIcon('heroicon-o-tag')
                ->color('primary')
                ->url(CategoryResource::getUrl('index')),
            Stat::make('Top Category', $topCategoryName)
                ->description("With {$topCategoryCount} events")
                ->descriptionIcon('heroicon-o-trophy')
                ->color('success')
                ->url($topCategory ? CategoryResource::getUrl('edit', ['record' => $topCategory]) : null),
            Stat::make('Avg Events/Category', $avgEventsPerCategory)
                ->description('Average events per category')
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color('warning'),
        ];
    }
}
