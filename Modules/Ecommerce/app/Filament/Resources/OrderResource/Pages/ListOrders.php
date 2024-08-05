<?php

namespace Modules\Ecommerce\Filament\Resources\OrderResource\Pages;

use Modules\Ecommerce\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Orders'),
            'pending' => Tab::make('Pending Orders')->modifyQueryUsing(function ($query) {
                $query->where('status', 'pending');
            }),
            'processing' => Tab::make('Processing Orders')->modifyQueryUsing(function ($query) {
                $query->where('status', 'processing');
            }),
            'completed' => Tab::make('Completed Orders')->modifyQueryUsing(function ($query) {
                $query->where('status', 'completed');
            }),
            'declined' => Tab::make('Declined Orders')->modifyQueryUsing(function ($query) {
                $query->where('status', 'declined');
            }),
            // 'cancelled' => Tab::make('Cancelled Orders')->modifyQueryUsing(function ($query) {
            //     $query->where('status', 'cancelled');
            // }),
        ];
    }
}
