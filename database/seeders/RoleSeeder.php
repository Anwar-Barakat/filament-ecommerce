<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'super-admin',
            'admin',
            'editor',
            'author',
            'contributor',
            'subscriber',
        ];

        foreach ($roles as $role) {
            if (is_null(Role::where('name', $role)->first())) {
                Role::create(['name' => $role]);
            }
        }

        $admin = User::create([
            'name'      => 'Anwar',
            'email'     => 'admin@admin.com',
            'password'  => Hash::make('123456'),
        ]);

        $admin->assignRole('super-admin');
    }
}
