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
            'view any appointments',
        	'view appointments',
        	'create appointments',
            'process appointments',
            'cancel appointments',
        	'edit appointments',
        	'delete appointments',
            'restore appointments',

            // Sub Appointment
            'view any sub appointments',
            'view sub appointments',
            'create sub appointments',
            'process sub appointments',
            'cancel sub appointments',
            'edit sub appointments',
            'delete sub appointments',
            'restore sub appointments',
            'force delete sub appointments',

        	// Car
            'view any cars',
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
            'view any owners',
            'view owners',
            'create owners',
            'edit owners',
            'delete owners',
            'restore owners',
            'force delete owners',

        	// Customer
            'view any customers',
        	'view customers',
        	'create customers',
        	'edit customers',
        	'delete customers',
            'restore customers',
            'force delete customers',

        	// Employee
            'view any employees',
        	'view employees',
        	'create employees',
        	'edit employees',
        	'delete employees',
            'restore employees',
            'force delete employees',

        	// Inspection
            'view any inspections',
        	'view inspections',
        	'create inspections',
        	'edit inspections',
        	'delete inspections',
            'restore inspections',
            'force delete inspections',

        	// Inspector
            'view any inspections',
        	'view inspectors',
        	'create inspectors',
        	'edit inspectors',
        	'delete inspectors',
            'restore inspectors',
            'force delete inspectors',

        	// Invoice
            'view any invoices',
        	'view invoices',
        	'create invoices',
        	'edit invoices',
        	'delete invoices',
            'restore invoices',
            'force delete invoices',

        	// Invoice Item
            'view any invoice items',
        	'view invoice items',
        	'create invoice items',
        	'edit invoice items',
        	'delete invoice items',
            'restore invoice items',
            'force delete invoice items',

        	// Payment Term
            'view any payment terms',
        	'view payment terms',
        	'create payment terms',
        	'edit payment terms',
        	'delete payment terms',
            'restore payment terms',
            'force delete payment terms',

        	// Pricing
            'view any pricings',
        	'view pricings',
        	'create pricings', 
        	'edit pricings',
        	'delete pricings',
            'restore pricings',
            'force delete pricings',

        	// Quotation
            'view any quotations',
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
            'view any quotation photos',
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
            'view any schedules',
        	'view schedules',
        	'create schedules',
        	'edit schedules',
        	'delete schedules',
            'restore schedules',
            'force delete schedules',

        	// Schedule Car
            'view any schedule cars',
        	'view schedule cars',
        	'create schedule cars',
        	'edit schedule cars',
        	'delete schedule cars',
            'restore schedule cars',
            'force delete schedule cars',

        	// Schedule Employee
            'view any schedule employees',
        	'view schedule employees',
        	'create schedule employees',
        	'edit schedule employees',
        	'delete shcedule employees',
            'restore schedule employees',
            'force delete schedule employees',

        	// Warranty
            'view any warranties',
        	'view warranties',
        	'create warranties',
        	'edit warranties',
        	'delete warranties',
            'restore warranties',
            'force delete warranties',

        	// Warranty Claim
            'view any warranty claims',
        	'view warranty claims',
        	'create warranty claims',
        	'edit warranty claims',
        	'delete warranty claims',
            'restore warranty claims',
            'force delete warranty claims',

        	// Work
            'view any works',
        	'view works',
        	'create works',
        	'edit works',
        	'delete works',
            'restore works',
            'force delete works',

            // Execute Work
            'execute works',
            'mark finished execute works',
            'mark unfinished execute works',
            'make continuation execute works',

        	// Work Contract
            'view any work contracts',
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
