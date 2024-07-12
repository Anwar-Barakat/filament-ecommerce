<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'User 1',
                'email' => 'user1@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'User 2',
                'email' => 'user2@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'User 3',
                'email' => 'user3@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'User 4',
                'email' => 'user4@example.com',
                'password' => Hash::make('password'),
            ]
        ];

        foreach ($users as $user) {
            if (is_null(User::where('email', $user['email'])->first())) {
                User::create($user);
            }
        }
    }
}
