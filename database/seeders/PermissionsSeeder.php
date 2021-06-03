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
        	'edit appointments',
        	'delete appointments',

        	// Car
        	'view cars',
        	'create cars',
        	'edit cars',
        	'delete cars',

        	// Company 
        	'register companies',
        	'edit companies',
        	'close companies',

        	// Customer
        	'view customers',
        	'create customers',
        	'edit customers',
        	'delete customers',

        	// Employee
        	'view employees',
        	'create employees',
        	'edit employees',
        	'delete employees',

        	// Inspection
        	'view inspections',
        	'create inspections',
        	'edit inspections',
        	'delete inspections',

        	// Inspector
        	'view inspectors',
        	'create inspectors',
        	'edit inspectors',
        	'delete inspectors',

        	// Invoice
        	'view invoices',
        	'create invoices',
        	'edit invoices',
        	'delete invoices',

        	// Invoice Item
        	'view invoice items',
        	'create invoice items',
        	'edit invoice items',
        	'delete invoice items',

        	// Payment Term
        	'view payment terms',
        	'create payment terms',
        	'edit payment terms',
        	'delete payment terms',

        	// Pricing
        	'view pricings',
        	'create pricings', 
        	'edit pricings',
        	'delete pricings',

        	// Quotation
        	'view quotations',
        	'create quotations',
        	'edit quotations',
        	'delete quotations',

        	// Quotation Photo
        	'view quotation photos',
        	'create quotation photos',
        	'edit quotation photos',
        	'delete quotation photos',

        	// Register Invitation
        	'view register invitations',
        	'send register invitations',

        	// Schedule
        	'view schedules',
        	'create schedules',
        	'edit schedules',
        	'delete schedules',

        	// Schedule Car
        	'view schedule cars',
        	'create schedule cars',
        	'edit schedule cars',
        	'delete schedule cars',

        	// Schedule Employee
        	'view schedule employees',
        	'create schedule employees',
        	'edit schedule employees',
        	'delete shcedult employees',

        	// Warranty
        	'view warranties',
        	'create warranties',
        	'edit warranties',
        	'delete warranties',

        	// Warranty Claim
        	'view warranty claims',
        	'create warranty claims',
        	'edit warranty claims',
        	'delete warranty claims',

        	// Work
        	'view works',
        	'create works',
        	'edit works',
        	'delete works',

        	// Work Activity
        	'view work activities',
        	'create work activities',
        	'edit work activities',
        	'delete work activities',

        	// Work Condition Photo
        	'view work condition photos',
        	'create work condition photos',
        	'edit work condition photos',
        	'delete work condition photos',

        	// Work Contract
        	'view work contracts',
        	'create work contracts',
        	'edit work contracts',
        	'delete work contracts',
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
