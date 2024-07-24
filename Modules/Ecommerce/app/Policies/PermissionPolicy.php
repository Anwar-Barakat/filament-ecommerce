<?php

namespace Modules\Ecommerce\Policies;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
       return $user->hasRole('super-admin');
    }

    public function view(User $user, Permission $permission): bool
    {
       return $user->hasRole('super-admin');
    }

    public function create(User $user): bool
    {
       return $user->hasRole('super-admin');
    }

    public function update(User $user, Permission $permission): bool
    {
       return $user->hasRole('super-admin');
    }

    public function delete(User $user, Permission $permission): bool
    {
       return $user->hasRole('super-admin');
    }

    public function restore(User $user, Permission $permission): bool
    {
       return $user->hasRole('super-admin');
    }

    public function forceDelete(User $user, Permission $permission): bool
    {
       return $user->hasRole('super-admin');
    }
}
