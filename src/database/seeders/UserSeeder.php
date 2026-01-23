<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'ダミーユーザー1',
            'email' => 'dummy1@example.com',
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'name' => 'ダミーユーザー2',
            'email' => 'dummy2@example.com',
            'password' => Hash::make('password'),
        ]);


        User::factory()->create([
            'name' => 'ダミーユーザー3',
            'email' => 'dummy3@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
