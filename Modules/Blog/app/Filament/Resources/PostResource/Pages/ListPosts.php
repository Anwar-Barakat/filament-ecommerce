<?php

namespace Modules\Blog\Filament\Resources\PostResource\Pages;

use Carbon\Carbon;
use Modules\Blog\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

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
