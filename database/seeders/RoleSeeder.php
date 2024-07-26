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
            ['name' => 'super-admin', 'slug' => 'super-admin'],
            ['name' => 'admin', 'slug' => 'admin'],
            ['name' => 'editor', 'slug' => 'editor'],
            ['name' => 'author', 'slug' => 'author'],
            ['name' => 'contributor', 'slug' => 'contributor'],
            ['name' => 'subscriber', 'slug' => 'subscriber'],
        ];

        foreach ($roles as $role) {
            if (is_null(Role::where('slug', $role)->first())) {
                Role::create($role);
            }
        }

        $admin = User::create([
            'name'      => 'Anwar',
            'email'     => 'admin@gmail.com',
            'password'  => Hash::make('123456'),
            'email_verified_at' => now(),
        ]);

        $admin->assignRole('super-admin');
    }
}
