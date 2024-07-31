<?php

namespace Modules\Ecommerce\Filament\Resources\ProductResource\Pages;

use Carbon\Carbon;
use Modules\Ecommerce\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'published' => Tab::make('Published')->modifyQueryUsing(function ($query) {
                $query->whereDate('published_at','<=', Carbon::today());
            }),
            'draft' => Tab::make('Draft')->modifyQueryUsing(function ($query) {
                $query->whereDate('published_at','>', Carbon::today());
            }),
        ];
    }
}
