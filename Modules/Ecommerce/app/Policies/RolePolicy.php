<?php

namespace Modules\Ecommerce\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
       return $user->hasRole('super-admin');
    }

    public function view(User $user, Role $role): bool
    {
       return $user->hasRole('super-admin');
    }

    public function create(User $user): bool
    {
       return $user->hasRole('super-admin');
    }

    public function update(User $user, Role $role): bool
    {
       return $user->hasRole('super-admin');
    }

    public function delete(User $user, Role $role): bool
    {
       return $user->hasRole('super-admin');
    }

    public function restore(User $user, Role $role): bool
    {
       return $user->hasRole('super-admin');
    }

    public function forceDelete(User $user, Role $role): bool
    {
       return $user->hasRole('super-admin');
    }
}
