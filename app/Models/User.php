<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;
use Filament\Panel;

class User extends Authenticatable implements HasAvatar, FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_url',
        'custom_fields',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'custom_fields'     => 'array'
    ];

    // filament avatar
    public function getFilamentAvatarUrl(): ?string {
        return $this->avatar_url ? Storage::url("$this->avatar_url") : null;
    }

    // filament access to dashboard
    public function canAccessPanel(Panel $panel): bool{
        return $this->hasRole('super-admin');
    }

    // filament custom fields
    public function getCustomFieldsJson(): ?string{
        return json_encode($this->custom_fields);
    }
}
