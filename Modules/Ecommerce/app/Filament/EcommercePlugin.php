<?php

namespace Modules\Ecommerce\Filament;

use Coolsam\Modules\Concerns\ModuleFilamentPlugin;
use Filament\Contracts\Plugin;
use Filament\Panel;

class EcommercePlugin implements Plugin
{
    use ModuleFilamentPlugin;

    public function getModuleName(): string
    {
        return 'Ecommerce';
    }

    public function getId(): string
    {
        return 'ecommerce';
    }

    public function boot(Panel $panel): void
    {
        // TODO: Implement boot() method.
    }
}
