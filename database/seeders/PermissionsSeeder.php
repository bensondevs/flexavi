<?php

namespace Database\Seeders;

use App\Models\{Employee\Employee, Permission\Permission, Permission\Role, User\User};
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $actionNames = [
            // Dashboard
            'access dashboard',

            // analytics
            'view any analytics',

            // faq
            'view any faqs',
            'view faqs',

            // Workday
            'view any workdays',
            'view workdays',
            'process workdays',
            'calculate workdays',
            'delete workdays',
            'restore workdays',

            // Worklist
            'view any worklists',
            'view worklists',
            'create worklists',
            'attach appointment worklists',
            'attach many appointments worklists',
            'detach appointment worklists',
            'detach many appointments worklists',
            'truncate appointments worklists',
            'move appointment worklists',
            'process worklists',
            'calculate worklists',
            'edit worklists',
            'delete worklists',
            'restore worklists',
            'force delete worklists',
            'sorting route worklists',

            // Appointment
            'view any appointments',
            'view appointments',
            'create appointments',
            'assign appointments employees',
            'unassign appointments employees',
            'process appointments',
            'reschedule appointments',
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
            'set driver car register time employees',
            'assign car register time employees',
            'unassign car register time employees',

            // Company
            'view any companies',
            'view companies',
            'register companies',
            'manage companies',
            'edit companies',
            'close companies',
            'delete companies',
            'force delete companies',
            'restore companies',

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
            'view city of customers',
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
            'set image employees',
            'edit employees',
            'delete employees',
            'restore employees',
            'force delete employees',

            // Permission
            'view any permissions',

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
            'edit invoices',
            'delete invoices',
            'restore invoices',
            'force delete invoices',
            'print invoices',
            'change status invoices',
            'send first reminder invoices',
            'send second reminder invoices',
            'send third reminder invoices',
            'send to debt collector invoices',
            'mark as paid invoices',

            'view any invoice reminders',
            'edit invoice reminders',

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
            'print quotations',
            'send quotations',
            'generate invoice quotations',
            'nullify quotations',
            'delete quotations',
            'restore quotations',
            'upload signed document quotations',
            'remove signed document quotations',
            'force delete quotations',
            'view any quotation logs',

            // Receipt
            'view any receipts',
            'view receipts',
            'create receipts',
            'edit receipts',
            'delete receipts',
            'restore receipts',
            'force delete receipts',

            // Owner Invitation
            'send employee register invitation',
            'view any invitation owners',
            'view pending invitation owners',
            'view pending invitation owner',
            'cancel owner invitations',

            // Employee invitations
            'send owner register invitation',
            'view pending invitation employees',
            'view pending invitation employee',
            'cancel employee invitations',

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
            'attach works',
            'attach many works',
            'detach works',
            'detach many works',
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
            'truncate works',

            // Execute Work
            'view any execute works',
            'view execute works',
            'create execute works',
            'edit execute works',
            'delete execute works',
            'restore execute works',
            'force delete execute works',

            // Execute Work
            'view any execute work photos',
            'upload execute work photos',
            'delete execute work photos',

            // Work Contract
            'view any work contracts',
            'view work contracts',
            'create work contracts',
            'edit work contracts',
            'send work contracts',
            'print work contracts',
            'delete work contracts',
            'restore work contracts',
            'force delete work contracts',
            'nullify work contracts',
            'set work contract as default format',
            'apply company format work contracts',

            'upload signed document work contracts',
            'remove signed document work contracts',

            // Address
            'view any addresses',
            'view addresses',
            'create addresses',
            'edit addresses',
            'delete addresses',
            'restore addresses',
            'force delete addresses',
            'pro6pp autocomplete address',

            // Log History
            'view any logs',
            'view logs',
            'restore logs',
            'delete logs',
            'force delete logs',

            // Post It
            'view any post its',
            'create post its',
            'assign user post its',
            'unassign user post its',
            'edit post its',
            'delete post its',
            'restore post its',
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

            // Mollie api
            'view any mollie payment methods',

            // subscription plans
            'view any subscription plans',

            // Subscription trial
            'start trial subscriptions',

            // Subscription
            'view any subscriptions',
            'view subscriptions',
            'purchase subscriptions',
            'activate subscriptions',
            'terminate subscriptions',

            // Setting
            'view any settings',
            'view settings',
            'edit settings',

            // notifications
            'view notifications',
            'mark read notifications',
            'mark unread notifications',
            'mark read all notifications',
            'mark unread all notifications',

            // helpdesk
            'view any help desks',
            'view help desks',
            'store help desks',
            'update help desks',
            'delete help desks',

            // work services
            'view any work services',
            'view work services',
            'create work services',
            'edit work services',
            'delete work services',
            'restore work services',
            'force delete work services',

            // customer notes
            'view any customer notes',
            'view customer notes',
            'create customer notes',
            'edit customer notes',
            'delete customer notes',
            'restore customer notes',
            'force delete customer notes',
        ];

        $rawPermissions = [];
        foreach ($actionNames as $actionName) {
            $rawPermissions[] = [
                'id' => generateUuid(),
                'name' => $actionName,
                'guard_name' => 'web',
                'created_at' => carbon()->now(),
                'updated_at' => carbon()->now(),
            ];
        }

        foreach (array_chunk($rawPermissions, 50) as $rawPermission) {
            Permission::insert($rawPermission);
        }

        $permissions = Permission::all();

        $ownerRole = Role::findByName('owner');
        $ownerRole->givePermissionTo($permissions);


        //   give the owners all permissions
        $owners = User::where('email', 'LIKE', 'owner%')->get();

        foreach ($owners as $owner) {
            $owner->givePermissionTo($permissions);
        }

        // give the employee random permissions
        $employees = Employee::with("user")->limit(10)->orderBy("created_at", "ASC")->get();
        foreach ($employees as $employee) {
            if ($user = $employee->user) {
                $user->givePermissionTo($permissions->shuffle()->take(rand(15, 25)));
            }
        }
    }
}
