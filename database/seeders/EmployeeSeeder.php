<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use Carbon\Carbon;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $employee = new Employee();
        $employee->created_at = Carbon::now();
        $employee->uuid = $faker->uuid;
        $employee->first_name = 'adi';
        $employee->last_name = 'yansah';
        $employee->date_of_birth = Carbon::parse('1993-04-16')->isoFormat('YYYY-MM-DD');
        $employee->email = 'adiyansahcode@gmail.com';
        $employee->phone = '087741413213';
        $employee->regency_id = 3216;
        $employee->address = 'Perumahan puri cendana jalan taman bromo 5 Blok G no 10';
        $employee->zip_code = '17510';
        $employee->ktp = '1111111';
        $employee->ktp_image = null;
        $employee->ktp_image_url = null;
        $employee->position_id = 3;
        $employee->bank_id = 1;
        $employee->bank_account = '1111111';
        $employee->save();
    }
}
