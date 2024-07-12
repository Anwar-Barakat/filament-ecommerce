<?php

namespace Modules\Ecommerce\Filament\Resources\UserResource\Pages;

use Modules\Ecommerce\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
