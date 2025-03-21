<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $user = User::create([
            'uuid' => Str::uuid(),
            'first_name' => 'Laziest',
            'last_name' => 'Ant',
            'username' => 'laziestant',
            'email' => 'laziestant@gmail.com',
            'profile' => '',
            'gender' => 'male',
            'password' => Hash::make('password'),
        ]);
    }
}
