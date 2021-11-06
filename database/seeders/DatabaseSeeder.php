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
        	RolesSeeder::class,
            PermissionsSeeder::class,
        	UsersSeeder::class,
            CompaniesSeeder::class,
            SettingsSeeder::class,
            CustomersSeeder::class,
            EmployeesSeeder::class,
            CarsSeeder::class,
            WorkdaysSeeder::class,
            WorklistsSeeder::class,
            AppointmentsSeeder::class,
            AppointmentCostsSeeder::class,
            SubAppointmentsSeeder::class,
            QuotationsSeeder::class,
            QuotationAttachmentsSeeder::class,
            WorksSeeder::class,
            ExecuteWorksSeeder::class,
            ExecuteWorkPhotosSeeder::class,
            InvoicesSeeder::class,
            InvoiceItemsSeeder::class,
            PaymentTermsSeeder::class,
            RegisterInvitationsSeeder::class,
            RevenuesSeeder::class,
            WorklistCarsSeeder::class,
            AppointmentEmployeesSeeder::class,
            WarrantiesSeeder::class,
            AddressesSeeder::class,
            CostsSeeder::class,
            CarRegisterTimesSeeder::class,
        ]);
    }
}