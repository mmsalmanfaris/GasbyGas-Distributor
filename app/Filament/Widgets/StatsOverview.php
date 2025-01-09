<?php

namespace App\Filament\Widgets;

use App\Models\OutletManagements;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            stat::make('Total Outlets', OutletManagements::count())
                ->icon('heroicon-o-archive-box')
                ->color('success')
                ->description('Increase of 25% in the last 30 days')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7,3,5,2,6,2,4,5,3,4,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30]),
            
            stat::make('Total Users', User::count())
                ->icon('heroicon-o-user-group')
                ->color('primary')
                ->description('Increase of 10% in the last 30 days')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7,3,5,2,6,2,4,5,3,4,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30]),
        ];
    }
}
