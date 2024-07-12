<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
    App\Providers\VoltServiceProvider::class,

    // Roles and Permissions
    Spatie\Permission\PermissionServiceProvider::class,
];
