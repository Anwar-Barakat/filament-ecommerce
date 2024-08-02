<?php

namespace Modules\Ecommerce\Filament\Widgets;

use App\Enum\OrderStatusEnum;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 2;
    protected static ?string $pollingInterval = '15s';

    protected static bool $isLazy = true;
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('Increase in users')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),
            Stat::make('Total Products', Product::count())
                ->description('Total products in app')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),
            Stat::make('Pending Orders', Order::where('status', OrderStatusEnum::PENDING->value)->count())
                ->description('Total products in app')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),
        ];
    }
}
