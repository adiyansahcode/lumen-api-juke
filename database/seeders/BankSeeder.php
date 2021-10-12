<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BankSeeder extends Seeder
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
        DB::table('bank')->truncate();

        // insert db
        DB::table('bank')->insert([
            [
                'created_at' => Carbon::now(),
                'uuid' => $faker->uuid,
                'name' => 'BCA',
            ],
            [
                'created_at' => Carbon::now(),
                'uuid' => $faker->uuid,
                'name' => 'Mandiri',
            ],
            [
                'created_at' => Carbon::now(),
                'uuid' => $faker->uuid,
                'name' => 'BRI',
            ],
            [
                'created_at' => Carbon::now(),
                'uuid' => $faker->uuid,
                'name' => 'BNI',
            ],
            [
                'created_at' => Carbon::now(),
                'uuid' => $faker->uuid,
                'name' => 'BTN',
            ],
        ]);
    }
}
