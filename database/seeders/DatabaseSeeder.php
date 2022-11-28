<?php

namespace Database\Seeders;

use App\Jobs\Developments\CreateInvoiceLogJob;
use App\Jobs\Developments\CreateQuotationLogJob;
use Artisan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{Storage};

class DatabaseSeeder extends Seeder
{
    /**
     * Define the public storage directories that can be
     * removed on storage reset
     *
     * @var array
     */
    private array $resetableStorageDirectories = [
        'do' => [
            'cars',
            'companies',
            'employees',
            'employees/invitations',
            'execute_works/photos',
            'quotations/attachments',
            'receipts',
            'users',
            'work_contracts/pdfs',
            'uploads',
        ],
    ];

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->resetStorage();
        $this->runSeeder();
        $this->seedJobs();
    }

    /**
     * Reset the application's storage
     *
     * @return void
     */
    public function resetStorage(): void
    {
        foreach ($this->resetableStorageDirectories as $disk => $directories) {
            foreach ($directories as $directory) {
                Storage::disk($disk)->deleteDirectory($directory);
            }
        }
    }

    /**
     * This method will seed master data which will be the base of application.
     *
     * @return void
     * @see \App\Console\Commands\SeedMasterData
     *      To the usage to seed only master data.
     */
    public function seedMasterData(): void
    {
        $this->call([
            // Application technical data seeders
            RolesSeeder::class,
            PermissionsSeeder::class,
            ModulePermissionsSeeder::class,
            SubscriptionPlansSeeder::class,

            // Application UX related seeders
            HelpDesksSeeder::class,
        ]);
    }

    /**
     * This method will seed testing data.
     *
     * @return void
     */
    private function seedTestingData(): void
    {
        $this->call([
            // UsersSeeder::class, /* Deprecated! */

            // Company in-house members seeders
            CompaniesSeeder::class,
            OwnersSeeder::class,
            EmployeesSeeder::class,
            // EmployeePermissionsSeeder::class, /* Deprecated */

            // Company member invitation seeder
            OwnerInvitationsSeeder::class,

            // Company SaaS seeders
            // SubscriptionsSeeder::class, /* Deprecated */

            // Company configuration seeders
            SettingsSeeder::class,
            CompanyWorkContractSettingsSeeder::class,

            // Company master data seeders
            AddressesSeeder::class,
            WorkServicesSeeder::class,
            // CarsSeeder::class, /* Will be used after MVP */
            // CarRegisterTimesSeeder::class, /* Will be used after MVP */

            // Company customers data seeders
            CustomersSeeder::class,
            CustomerAddressesSeeder::class,
            CustomerNotesSeeder::class,

            // Company business related data seeders
            InvoicesSeeder::class,
            QuotationsSeeder::class,
            PostItsSeeder::class,
            WorkContractsSeeder::class,
            EmployeeInvitationsSeeder::class,


            // Company notification and log seeders
            NotificationsSeeder::class,
            LogsSeeder::class,
        ]);
    }

    /**
     * Run the data seeders
     *
     * @return void
     */
    public function runSeeder(): void
    {
        $this->seedMasterData();
        $this->seedTestingData();
    }

    /**
     * Seed the application's jobs
     *
     * @return void
     */
    public function seedJobs(): void
    {
        Artisan::call('queue:clear');
        dispatch(new CreateQuotationLogJob());
        dispatch(new CreateInvoiceLogJob());
    }
}
