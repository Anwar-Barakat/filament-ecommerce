<?php

namespace Modules\Ecommerce\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole('super-admin');
    }

    public function view(User $user): bool
    {
        return $user->hasRole('super-admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('super-admin');
    }

    public function update(User $user): bool
    {
        return $user->hasRole('super-admin');
    }

    public function delete(User $user): bool
    {
        return $user->hasRole('super-admin');
    }

    public function restore(User $user): bool
    {
        return $user->hasRole('super-admin');
    }

    public function forceDelete(User $user): bool
    {
        return $user->hasRole('super-admin');
    }
}
