<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Role;
use App\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $actionNames = [
        	// Appointment
        	'view appointments',
        	'create appointments',
            'process appointments',
            'cancel appointments',
        	'edit appointments',
        	'delete appointments',
            'restore appointments',

            // Sub Appointment
            'view sub appointments',
            'create sub appointments',
            'process sub appointments',
            'cancel sub appointments',
            'edit sub appointments',
            'delete sub appointments',
            'restore sub appointments',
            'force delete sub appointments',

        	// Car
        	'view cars',
        	'create cars',
        	'edit cars',
        	'delete cars',
            'restore cars',
            'force delete cars',

        	// Company 
        	'register companies',
            'manage companies',
        	'edit companies',
        	'close companies',
            'force delete companies',

            // Owners
            'view owners',
            'create owners',
            'edit owners',
            'delete owners',
            'restore owners',
            'force delete owners',

        	// Customer
        	'view customers',
        	'create customers',
        	'edit customers',
        	'delete customers',
            'restore customers',
            'force delete customers',

        	// Employee
        	'view employees',
        	'create employees',
        	'edit employees',
        	'delete employees',
            'restore employees',
            'force delete employees',

        	// Inspection
        	'view inspections',
        	'create inspections',
        	'edit inspections',
        	'delete inspections',
            'restore inspections',
            'force delete inspections',

        	// Inspector
        	'view inspectors',
        	'create inspectors',
        	'edit inspectors',
        	'delete inspectors',
            'restore inspectors',
            'force delete inspectors',

        	// Invoice
        	'view invoices',
        	'create invoices',
        	'edit invoices',
        	'delete invoices',
            'restore invoices',
            'force delete invoices',

        	// Invoice Item
        	'view invoice items',
        	'create invoice items',
        	'edit invoice items',
        	'delete invoice items',
            'restore invoice items',
            'force delete invoice items',

        	// Payment Term
        	'view payment terms',
        	'create payment terms',
        	'edit payment terms',
        	'delete payment terms',
            'restore payment terms',
            'force delete payment terms',

        	// Pricing
        	'view pricings',
        	'create pricings', 
        	'edit pricings',
        	'delete pricings',
            'restore pricings',
            'force delete pricings',

        	// Quotation
        	'view quotations',
        	'create quotations',
        	'edit quotations',
            'send quotations',
            'print quotations',
            'revise quotations',
            'honor quotations',
            'cancel quotations',
        	'delete quotations',
            'restore quotations',
            'force delete quotations',
            'add quotation attachments',
            'remove quotation attachments',

            // Quotation Revisions
            'apply revision quotations',
            'view quotation revisions',
            'create quotation revisions',
            'edit quotation revisions',
            'delete quotation revisions',
            'force delete quotation revisions',

        	// Quotation Photo
        	'view quotation photos',
        	'create quotation photos',
        	'edit quotation photos',
        	'delete quotation photos',
            'restore quotation photos',
            'force delete quotation photos',

        	// Register Invitation
        	'view register invitations',
        	'send register invitations',

        	// Schedule
        	'view schedules',
        	'create schedules',
        	'edit schedules',
        	'delete schedules',
            'restore schedules',
            'force delete schedules',

        	// Schedule Car
        	'view schedule cars',
        	'create schedule cars',
        	'edit schedule cars',
        	'delete schedule cars',
            'restore schedule cars',
            'force delete schedule cars',

        	// Schedule Employee
        	'view schedule employees',
        	'create schedule employees',
        	'edit schedule employees',
        	'delete shcedule employees',
            'restore schedule employees',
            'force delete schedule employees',

        	// Warranty
        	'view warranties',
        	'create warranties',
        	'edit warranties',
        	'delete warranties',
            'restore warranties',
            'force delete warranties',

        	// Warranty Claim
        	'view warranty claims',
        	'create warranty claims',
        	'edit warranty claims',
        	'delete warranty claims',
            'restore warranty claims',
            'force delete warranty claims',

        	// Work
        	'view works',
        	'create works',
        	'edit works',
        	'delete works',
            'restore works',
            'force delete works',

        	// Work Activity
        	'view work activities',
        	'create work activities',
        	'edit work activities',
        	'delete work activities',
            'restore work activities',
            'force delete work activities',

        	// Work Condition Photo
        	'view work condition photos',
        	'create work condition photos',
        	'edit work condition photos',
        	'delete work condition photos',
            'restore work condition photos',
            'force delete work condition photos',

        	// Work Contract
        	'view work contracts',
        	'create work contracts',
        	'edit work contracts',
        	'delete work contracts',
            'restore work contracts',
            'force delete work contracts',
        ];

        $rawPermissions = [];
        foreach ($actionNames as $actionName) {
        	array_push($rawPermissions, [
        		'id' => generateUuid(),
        		'name' => $actionName,
        		'guard_name' => 'web',
        		'created_at' => carbon()->now(),
        		'updated_at' => carbon()->now(),
        	]);
        }
        Permission::insert($rawPermissions);

        $owner = Role::where('name', 'owner')->first();
        foreach ($actionNames as $actionName) 
        	$owner->givePermissionTo($actionName);
    }
}
