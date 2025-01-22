<?php

namespace Database\Seeders;

use App\Models\ProfileLevel;
use Illuminate\Database\Seeder;

class ProfileLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $levels = ['Master', 'Administrator', 'Default'];

        foreach ($levels as $level) {
            ProfileLevel::create(['name' => $level]);
        }
    }
}
