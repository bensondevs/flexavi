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
            CustomersSeeder::class,
            EmployeesSeeder::class,
            CarsSeeder::class,
            WorkdaysSeeder::class,
            WorklistsSeeder::class,
            AppointmentsSeeder::class,
            AppointmentWorkersSeeder::class,
            // AppointmentWorksSeeder::class,
            AppointmentCostsSeeder::class,
            SubAppointmentsSeeder::class,
            QuotationsSeeder::class,
            // QuotationWorksSeeder::class,
            QuotationAttachmentsSeeder::class,
            WorksSeeder::class,
            InvoicesSeeder::class,
            InvoiceItemsSeeder::class,
            PaymentTermsSeeder::class,
            RegisterInvitationsSeeder::class,
            AddressesSeeder::class,
        ]);
    }
}