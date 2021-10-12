<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        // truncate db
        DB::table('position')->truncate();

        // insert db
        DB::table('position')->insert([
            [
                'created_at' => Carbon::now(),
                'uuid' => $faker->uuid,
                'name' => 'Manager',
            ],
            [
                'created_at' => Carbon::now(),
                'uuid' => $faker->uuid,
                'name' => 'Supervisor',
            ],
            [
                'created_at' => Carbon::now(),
                'uuid' => $faker->uuid,
                'name' => 'Staff',
            ],
            [
                'created_at' => Carbon::now(),
                'uuid' => $faker->uuid,
                'name' => 'Etc',
            ],
        ]);
    }
}
