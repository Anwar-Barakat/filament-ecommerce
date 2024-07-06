<?php

namespace Modules\Ecommerce\Filament\Resources\OrderResource\Pages;

use Modules\Ecommerce\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;
}
