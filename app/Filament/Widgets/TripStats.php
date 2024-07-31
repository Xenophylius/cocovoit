<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Trip;

class TripStats extends BaseWidget
{

    protected int | string | array $columnSpan = 3;
    protected function getCards(): array
    {
        return [
            Card::make('Total Trips', Trip::count())
                ->description('Number of trips')
                ->descriptionIcon('heroicon-s-briefcase'),
        ];
    }
}
