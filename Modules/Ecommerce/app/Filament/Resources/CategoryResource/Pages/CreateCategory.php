<?php

namespace Modules\Ecommerce\Filament\Resources\CategoryResource\Pages;

use Modules\Ecommerce\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;
}
