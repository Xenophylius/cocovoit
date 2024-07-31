<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\User;

class UserStats extends BaseWidget
{

    protected int | string | array $columnSpan = 3;

    protected function getCards(): array
    {
        return [
            Card::make('Total Users', User::count())
                ->description('Number of users')
                ->descriptionIcon('heroicon-s-user'),
        ];
    }
}
