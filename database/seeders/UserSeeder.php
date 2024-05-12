<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'john',
            'email' => 'john1@yopmail.com',
            'password' => \Hash::make('password')
        ]);

        User::create([
            'name' => 'jon',
            'email' => 'john2@yopmail.com',
            'password' => \Hash::make('password')
        ]);

        User::create([
            'name' => 'joan',
            'email' => 'john3@yopmail.com',
            'password' => \Hash::make('password')
        ]);
    }
}
