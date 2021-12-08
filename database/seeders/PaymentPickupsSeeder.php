<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\{ PaymentPickup, Company, Appointment };

class PaymentPickupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Company::all() as $company) {
            PaymentPickup::factory()
                ->for($company)
                ->count(rand(10, 20))
                ->create();
        }
    }
}