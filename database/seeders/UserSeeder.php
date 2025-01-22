<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
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
        $masterLevel = \App\Models\ProfileLevel::where('name', 'Master')->first();
        
        User::create([
            'name' => 'Master User',
            'email' => 'master@example.com',
            'password' => Hash::make('password'), // Altere a senha conforme necessário
            'profile_level_id' => $masterLevel->id
        ]);
    }
}
