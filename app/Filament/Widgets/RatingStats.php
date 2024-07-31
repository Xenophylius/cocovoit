<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Rating;

class RatingStats extends BaseWidget
{

    protected int | string | array $columnSpan = 3;
    protected function getCards(): array
    {
        return [
            Card::make('Total Ratings', Rating::count())
                ->description('Number of ratings')
                ->descriptionIcon('heroicon-s-star'),
        ];
    }
}
