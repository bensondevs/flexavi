<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{ Role, Permission };

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
            // Workday
            'view any workdays',
            'view workdays',
            'process workdays',
            'calculate workdays',

            // Worklist
            'view any worklists',
            'view worklists',
            'create worklists',
            'attach appointment worklists',
            'attach many appointments worklists',
            'detach appointment worklists',
            'detach many appointments worklists',
            'truncate appointments worklists',
            'process worklists',
            'calculate worklists',
            'edit worklists',
            'delete worklists',
            'restore worklists',
            'force delete worklists',

        	// Appointment
            'view any appointments',
        	'view appointments',
        	'create appointments',
            'assign appointments employees',
            'unassign appointments employees',
            'process appointments',
            'execute appointments',
            'generate invoice appointments',
            'cancel appointments',
        	'edit appointments',
        	'delete appointments',
            'restore appointments',

            // Sub Appointment
            'view any sub appointments',
            'view sub appointments',
            'create sub appointments',
            'edit sub appointments',
            'process sub appointments',
            'execute sub appointments',
            'reschedule sub appointments',
            'cancel sub appointments',
            'delete sub appointments',
            'restore sub appointments',
            'force delete sub appointments',

            // Cost
            'view any costs',
            'view costs',
            'create costs',
            'record costs',
            'unrecord costs',
            'truncate costs',
            'edit costs',
            'delete costs',
            'force delete costs',

            // Revenue
            'view any revenues',
            'view revenues',
            'create revenues',
            'edit revenues',
            'delete revenues',
            'restore revenues',
            'force delete revenues',

        	// Car
            'view any cars',
        	'view cars',
        	'create cars',
            'set image cars',
        	'edit cars',
        	'delete cars',
            'restore cars',
            'force delete cars',

            // Car Register Times
            'view any car register times',
            'view car register times',
            'create car register times',
            'register car times',
            'register worklist car times',
            'mark out car register times',
            'mark return car register times',
            'edit car register times',
            'delete car register times',
            'force delete car register times',

            // Car Register Time Employees
            'view any car register time employees',
            'view car register time employees',
            'assign car register time employees',
            'set driver car register time employees',
            'unassign car register time employees',

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
            'view any inspectors',
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
            'send invoices',
            'print invoices',
            'change status invoices',
            'send reminder invoices',
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
            'generate invoice quotations',
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

            // Receipt
            'view any receipts',
            'view receipts',
            'create receipts',
            'edit receipts',
            'delete receipts',
            'restore receipts',
            'force delete receipts',

        	// Register Invitation
        	'view register invitations',
            'send owner register invitations',
            'send employee register invitations',

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
            'execute works',
            'process works',
            'mark finish works',
            'mark unfinish works',
        	'edit works',
        	'delete works',
            'restore works',
            'force delete works',

            // Execute Work
            'view any execute works',
            'mark finished execute works',
            'mark unfinished execute works',
            'make continuation execute works',
            'delete execute works',
            'restore execute works',

            // Execute Work
            'view any execute work photos',
            'upload execute work photos',
            'delete execute work photos',

        	// Work Contract
            'view any work contracts',
        	'view work contracts',
        	'create work contracts',
        	'edit work contracts',
        	'delete work contracts',
            'restore work contracts',
            'force delete work contracts',

            // Address
            'view any addresses',
            'view addresses',
            'create addresses',
            'edit addresses',
            'delete addresses',
            'restore addresses',
            'force delete addresses',

            // Post It
            'view any post its',
            'create post its',
            'assign user post its',
            'unassign user post its',
            'edit post its',
            'delete post its',
            'force delete post its',

            // Payment Pickup
            'view any payment pickups',
            'create payment pickups',
            'view payment pickups',
            'edit payment pickups',
            'pickup payment pickups',
            'delete payment pickups',
            'force delete payment pickups',
            'restore payment pickups',
            'add pickupable payment pickups',
            'add multiple pickupables payment pickups',
            'remove pickupable payment pickups',
            'remove multiple pickupables payment pickups',
            'truncate pickupables payment pickups',
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
        foreach ($actionNames as $actionName) {
        	$owner->givePermissionTo($actionName);
        }
    }
}
