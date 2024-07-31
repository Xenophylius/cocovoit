<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use App\Filament\Widgets\UserStats;
use App\Filament\Widgets\TripStats;
use App\Filament\Widgets\RatingStats;

class FilamentServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Filament::serving(function () {
            Filament::registerWidgets([
                UserStats::class,
                TripStats::class,
                RatingStats::class,
            ]);
        });
    }
}
