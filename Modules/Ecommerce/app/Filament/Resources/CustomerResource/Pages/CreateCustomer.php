<?php

namespace Modules\Ecommerce\Filament\Resources\CustomerResource\Pages;

use Modules\Ecommerce\Filament\Resources\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;
}
