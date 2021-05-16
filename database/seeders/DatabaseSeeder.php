<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
        	RolesPermissionsSeeder::class,
        	UsersSeeder::class,
            CompaniesSeeder::class,
            CustomersSeeder::class,
            EmployeesSeeder::class,
            CarsSeeder::class,
            AppointmentsSeeder::class,
            AppointmentWorkersSeeder::class,
        ]);
    }
}