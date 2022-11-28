<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->seedDashboardSettings();
        $this->seedCompanySettings();
        $this->seedEmployeeSettings();
        $this->seedCustomerSettings();
        $this->seedQuotationSettings();
    }

    /**
     * Seed dashboard settings.
     *
     * @return void
     */
    private function seedDashboardSettings(): void
    {
        $this->call(DashboardSettingsSeeder::class);
    }

     /**
     * Seed Company settings.
     *
     * @return void
     */
    private function seedCompanySettings(): void
    {
        $this->call(CompanySettingsSeeder::class);
    }

     /**
     * Seed Employee settings.
     *
     * @return void
     */
    private function seedEmployeeSettings(): void
    {
        $this->call(EmployeeSettingsSeeder::class);
    }

    /**
     * Seed Customer settings.
     *
     * @return void
     */
    private function seedCustomerSettings(): void
    {
        $this->call(CustomerSettingsSeeder::class);
    }

     /**
     * Seed Quotation settings.
     *
     * @return void
     */
    private function seedQuotationSettings(): void
    {
        $this->call(QuotationSettingsSeeder::class);
    }
}
