<?php

namespace App\Providers;

use App\Policies\{Company\Address\AddressPolicy,
    Company\Analytic\AnalyticPolicy,
    Company\Appointment\AppointmentPolicy,
    Company\Appointment\SubAppointmentPolicy,
    Company\Car\CarPolicy,
    Company\Car\CarRegisterTimeEmployeePolicy,
    Company\Car\CarRegisterTimePolicy,
    Company\Company\CompanyPolicy,
    Company\Cost\CostPolicy,
    Company\Customer\CustomerNotePolicy,
    Company\Customer\CustomerPolicy,
    Company\Employee\EmployeeInvitationPolicy,
    Company\Employee\EmployeePolicy,
    Company\ExecuteWork\ExecuteWorkPhotoPolicy,
    Company\ExecuteWork\ExecuteWorkPolicy,
    Company\FAQ\FAQPolicy,
    Company\HelpDesk\HelpDeskPolicy,
    Company\Inspection\InspectionPolicy,
    Company\Invoice\InvoicePolicy,
    Company\Invoice\InvoiceReminderPolicy,
    Company\Log\LogPolicy,
    Company\Mollie\MolliePolicy,
    Company\Notification\NotificationPolicy,
    Company\Owner\OwnerInvitationPolicy,
    Company\Owner\OwnerPolicy,
    Company\PaymentPickup\PaymentPickupPolicy,
    Company\Permission\PermissionPolicy,
    Company\PostIt\PostItPolicy,
    Company\Quotation\QuotationPolicy,
    Company\Receipt\ReceiptPolicy,
    Company\Revenue\RevenuePolicy,
    Company\Setting\SettingPolicy,
    Company\Subscription\SubscriptionPlanPolicy,
    Company\Subscription\SubscriptionPolicy,
    Company\Subscription\SubscriptionTrialPolicy,
    Company\Warranty\WarrantyPolicy,
    Company\Warranty\WarrantyWorkPolicy,
    Company\Work\WorkPolicy,
    Company\WorkContract\WorkContractPolicy,
    Company\Workday\WorkdayPolicy,
    Company\Worklist\WorklistPolicy,
    Company\WorkService\WorkServicePolicy
};
use App\Policies\Company\Account\AccountPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            return $user->hasRole('admin') ? true : null;
        });

        // Account
        Gate::define('is-account-deleted', [AccountPolicy::class, 'isAccountDeleted']);

        // Company
        Gate::define('view-any-company', [CompanyPolicy::class, 'viewAny']);
        Gate::define('view-company', [CompanyPolicy::class, 'view']);
        Gate::define('create-company', [CompanyPolicy::class, 'create']);
        Gate::define('register-company', [CompanyPolicy::class, 'register']);
        Gate::define('edit-company', [CompanyPolicy::class, 'update']);
        Gate::define('delete-company', [CompanyPolicy::class, 'delete']);
        Gate::define('restore-company', [CompanyPolicy::class, 'restore']);
        Gate::define('force-delete-company', [
            CompanyPolicy::class,
            'forceDelete',
        ]);
        // Mollie
        Gate::define('view-any-mollie-payment-method', [MolliePolicy::class, 'viewAnyPaymentMethods']);


        // Subscription plan
        Gate::define('view-any-subscription-plan', [SubscriptionPlanPolicy::class, 'viewAny']);

        // Subscription trial
        Gate::define('start-trial-subscription', [SubscriptionTrialPolicy::class, 'startTrial']);

        // Subscription
        Gate::define('view-subscription', [SubscriptionPolicy::class, 'view']);
        Gate::define('view-any-subscription', [SubscriptionPolicy::class, 'viewAny']);
        Gate::define('purchase-subscription', [SubscriptionPolicy::class, 'purchase']);
        Gate::define('activate-subscription', [SubscriptionPolicy::class, 'activate']);
        Gate::define('terminate-subscription', [SubscriptionPolicy::class, 'terminate']);

        // Analytic
        Gate::define('view-any-analytic', [
            AnalyticPolicy::class,
            'viewAny',
        ]);

        // Appointment
        Gate::define('view-any-appointment', [
            AppointmentPolicy::class,
            'viewAny',
        ]);
        Gate::define('view-appointment', [AppointmentPolicy::class, 'view']);
        Gate::define('create-appointment', [
            AppointmentPolicy::class,
            'create',
        ]);
        Gate::define('generate-invoice-appointment', [
            AppointmentPolicy::class,
            'generateInvoice',
        ]);
        Gate::define('update-appointment', [
            AppointmentPolicy::class,
            'update',
        ]);
        Gate::define('cancel-appointment', [
            AppointmentPolicy::class,
            'cancel',
        ]);
        Gate::define('assign-appointment-employee', [
            AppointmentPolicy::class,
            'assignEmployee',
        ]);
        Gate::define('unassign-appointment-employee', [
            AppointmentPolicy::class,
            'unassignEmployee',
        ]);
        Gate::define('execute-appointment', [
            AppointmentPolicy::class,
            'execute',
        ]);
        Gate::define('process-appointment', [
            AppointmentPolicy::class,
            'process',
        ]);
        Gate::define('calculate-appointment', [
            AppointmentPolicy::class,
            'calculate',
        ]);
        Gate::define('reschedule-appointment', [
            AppointmentPolicy::class,
            'reschedule',
        ]);
        Gate::define('delete-appointment', [
            AppointmentPolicy::class,
            'delete',
        ]);
        Gate::define('restore-appointment', [
            AppointmentPolicy::class,
            'restore',
        ]);
        Gate::define('force-delete-appointment', [
            AppointmentPolicy::class,
            'forceDelete',
        ]);

        // Sub Appointment
        Gate::define('view-any-sub-appointment', [
            SubAppointmentPolicy::class,
            'viewAny',
        ]);
        Gate::define('view-sub-appointment', [
            SubAppointmentPolicy::class,
            'view',
        ]);
        Gate::define('create-sub-appointment', [
            SubAppointmentPolicy::class,
            'create',
        ]);
        Gate::define('edit-sub-appointment', [
            SubAppointmentPolicy::class,
            'update',
        ]);
        Gate::define('process-sub-appointment', [
            SubAppointmentPolicy::class,
            'process',
        ]);
        Gate::define('execute-sub-appointment', [
            SubAppointmentPolicy::class,
            'execute',
        ]);
        Gate::define('cancel-sub-appointment', [
            SubAppointmentPolicy::class,
            'cancel',
        ]);
        Gate::define('reschedule-sub-appointment', [
            SubAppointmentPolicy::class,
            'reschedule',
        ]);
        Gate::define('delete-sub-appointment', [
            SubAppointmentPolicy::class,
            'delete',
        ]);
        Gate::define('restore-sub-appointment', [
            SubAppointmentPolicy::class,
            'restore',
        ]);
        Gate::define('force-delete-sub-appointment', [
            SubAppointmentPolicy::class,
            'forceDelete',
        ]);

        // Costs
        Gate::define('view-any-cost', [CostPolicy::class, 'viewAny']);
        Gate::define('view-cost', [CostPolicy::class, 'view']);
        Gate::define('create-cost', [CostPolicy::class, 'create']);
        Gate::define('record-cost', [CostPolicy::class, 'record']);
        Gate::define('record-many-cost', [CostPolicy::class, 'recordMany']);
        Gate::define('unrecord-cost', [CostPolicy::class, 'unrecord']);
        Gate::define('unrecord-many-cost', [CostPolicy::class, 'unrecordMany']);
        Gate::define('truncate-cost', [CostPolicy::class, 'truncate']);
        Gate::define('edit-cost', [CostPolicy::class, 'update']);
        Gate::define('delete-cost', [CostPolicy::class, 'delete']);
        Gate::define('restore-cost', [CostPolicy::class, 'restore']);
        Gate::define('force-delete-cost', [CostPolicy::class, 'forceDelete']);

        // Revenues
        Gate::define('view-any-revenue', [RevenuePolicy::class, 'viewAny']);
        Gate::define('view-revenue', [RevenuePolicy::class, 'view']);
        Gate::define('create-revenue', [RevenuePolicy::class, 'create']);
        Gate::define('update-revenue', [RevenuePolicy::class, 'update']);
        Gate::define('delete-revenue', [RevenuePolicy::class, 'delete']);
        Gate::define('restore-revenue', [RevenuePolicy::class, 'restore']);
        Gate::define('force-delete-revenue', [
            RevenuePolicy::class,
            'forceDelete',
        ]);

        // Receipts
        Gate::define('view-any-receipt', [ReceiptPolicy::class, 'viewAny']);
        Gate::define('view-receipt', [ReceiptPolicy::class, 'view']);
        Gate::define('create-receipt', [ReceiptPolicy::class, 'create']);
        Gate::define('edit-receipt', [ReceiptPolicy::class, 'update']);
        Gate::define('delete-receipt', [ReceiptPolicy::class, 'delete']);
        Gate::define('restore-receipt', [ReceiptPolicy::class, 'restore']);
        Gate::define('force-delete-receipt', [
            ReceiptPolicy::class,
            'forceDelete',
        ]);

        // Quotation
        Gate::define('view-any-quotation', [QuotationPolicy::class, 'viewAny']);
        Gate::define('view-any-customer-quotation', [
            QuotationPolicy::class,
            'viewAnyCustomer',
        ]);
        Gate::define('view-any-employee-quotation', [
            QuotationPolicy::class,
            'viewAnyEmployee',
        ]);
        Gate::define('view-quotation', [QuotationPolicy::class, 'view']);
        Gate::define('create-quotation', [QuotationPolicy::class, 'create']);
        Gate::define('create-quotation-with-appointment', [
            QuotationPolicy::class,
            'createWithAppointment',
        ]);
        Gate::define('edit-quotation', [QuotationPolicy::class, 'update']);
        Gate::define('draft-quotation', [QuotationPolicy::class, 'draft']);
        Gate::define('send-quotation', [QuotationPolicy::class, 'send']);
        Gate::define('resend-quotation', [QuotationPolicy::class, 'send']);
        Gate::define('print-quotation', [QuotationPolicy::class, 'print']);
        Gate::define('revise-quotation', [QuotationPolicy::class, 'revise']);
        Gate::define('honor-quotation', [QuotationPolicy::class, 'honor']);
        Gate::define('upload-signature-quotation', [QuotationPolicy::class, 'uploadSignature']);
        Gate::define('cancel-quotation', [QuotationPolicy::class, 'cancel']);
        Gate::define('generate-invoice-quotation', [QuotationPolicy::class, 'generateInvoice']);
        Gate::define('nullify-quotation', [QuotationPolicy::class, 'nullify']);
        Gate::define('delete-quotation', [QuotationPolicy::class, 'delete']);
        Gate::define('restore-quotation', [QuotationPolicy::class, 'restore']);
        Gate::define('force-delete-quotation', [QuotationPolicy::class, 'forceDelete']);
        Gate::define('add-quotation-attachment', [QuotationPolicy::class, 'addAttachment']);
        Gate::define('remove-quotation-attachment', [QuotationPolicy::class, 'removeAttachment']);
        Gate::define('upload-signed-doc-quotation', [QuotationPolicy::class, 'uploadSignedDoc']);
        Gate::define('remove-signed-doc-quotation', [QuotationPolicy::class, 'removeSignedDoc']);
        Gate::define('view-any-quotation-log', [QuotationPolicy::class, 'viewAnyQuotationLog']);

        // Inspection
        Gate::define('view-any-inspection', [InspectionPolicy::class, 'viewAny']);
        Gate::define('view-any-customer-inspection', [
            InspectionPolicy::class,
            'viewAnyCustomer',
        ]);
        Gate::define('view-any-employee-inspection', [
            InspectionPolicy::class,
            'viewAnyEmployee',
        ]);

        Gate::define('view-inspection', [InspectionPolicy::class, 'view']);
        Gate::define('create-inspection', [InspectionPolicy::class, 'create']);

        Gate::define('delete-inspection', [InspectionPolicy::class, 'delete']);
        Gate::define('restore-inspection', [InspectionPolicy::class, 'restore']);
        Gate::define('force-delete-inspection', [
            InspectionPolicy::class,
            'forceDelete',
        ]);


        // Execute work
        Gate::define('view-any-execute-work', [ExecuteWorkPolicy::class, 'viewAny']);
        Gate::define('view-any-customer-execute-work', [
            ExecuteWorkPolicy::class,
            'viewAnyCustomer',
        ]);
        Gate::define('view-execute-work', [ExecuteWorkPolicy::class, 'view']);
        Gate::define('create-execute-work', [ExecuteWorkPolicy::class, 'create']);

        Gate::define('delete-execute-work', [ExecuteWorkPolicy::class, 'delete']);
        Gate::define('restore-execute-work', [ExecuteWorkPolicy::class, 'restore']);
        Gate::define('force-delete-execute-work', [
            ExecuteWorkPolicy::class,
            'forceDelete',
        ]);

        // Invoices
        Gate::define('view-any-invoice', [InvoicePolicy::class, 'viewAny']);
        Gate::define('view-any-customer-quotation', [QuotationPolicy::class, 'viewAnyCustomer']);
        Gate::define('view-invoice', [InvoicePolicy::class, 'view']);
        Gate::define('print-invoice', [InvoicePolicy::class, 'print']);
        Gate::define('create-invoice', [InvoicePolicy::class, 'create']);
        Gate::define('send-invoice', [InvoicePolicy::class, 'send']);
        Gate::define('resend-invoice', [InvoicePolicy::class, 'send']);
        Gate::define('draft-invoice', [InvoicePolicy::class, 'draft']);
        Gate::define('update-invoice', [InvoicePolicy::class, 'update']);
        Gate::define('change-status-invoice', [InvoicePolicy::class, 'changeStatus']);
        Gate::define('send-reminder-invoice', [InvoicePolicy::class, 'sendReminder']);
        Gate::define('send-first-reminder-invoice', [InvoicePolicy::class, 'sendFirstReminder']);
        Gate::define('send-second-reminder-invoice', [InvoicePolicy::class, 'sendSecondReminder']);
        Gate::define('send-third-reminder-invoice', [InvoicePolicy::class, 'sendThirdReminder']);
        Gate::define('send-to-debt-collector-invoice', [InvoicePolicy::class, 'sendThirdReminder']);
        Gate::define('delete-invoice', [InvoicePolicy::class, 'delete']);
        Gate::define('restore-invoice', [InvoicePolicy::class, 'restore']);
        Gate::define('force-delete-invoice', [InvoicePolicy::class, 'forceDelete']);

        // Invoice reminders
        Gate::define('view-any-invoice-reminder', [InvoiceReminderPolicy::class, 'viewAny']);
        Gate::define('edit-invoice-reminder', [InvoiceReminderPolicy::class, 'update']);


        // Work
        Gate::define('view-any-work', [WorkPolicy::class, 'viewAny']);
        Gate::define('view-work', [WorkPolicy::class, 'view']);
        Gate::define('create-work', [WorkPolicy::class, 'create']);
        Gate::define('attach-work', [WorkPolicy::class, 'attach']);
        Gate::define('attach-many-work', [WorkPolicy::class, 'attachMany']);
        Gate::define('detach-work', [WorkPolicy::class, 'detach']);
        Gate::define('detach-many-work', [WorkPolicy::class, 'detachMany']);
        Gate::define('truncate-work', [WorkPolicy::class, 'truncate']);
        Gate::define('mark-finish-work', [WorkPolicy::class, 'markFinish']);
        Gate::define('edit-work', [WorkPolicy::class, 'update']);
        Gate::define('delete-work', [WorkPolicy::class, 'delete']);
        Gate::define('restore-work', [WorkPolicy::class, 'restore']);
        Gate::define('force-delete-work', [WorkPolicy::class, 'forceDelete']);

        // notification
        Gate::define('view-notification', [
            NotificationPolicy::class,
            'viewAny',
        ]);
        Gate::define('mark-read-notification', [
            NotificationPolicy::class,
            'markRead',
        ]);
        Gate::define('mark-unread-notification', [
            NotificationPolicy::class,
            'markUnread',
        ]);
        Gate::define('mark-read-all-notification', [
            NotificationPolicy::class,
            'markAllRead',
        ]);
        Gate::define('mark-unread-all-notification', [
            NotificationPolicy::class,
            'markAllUnread',
        ]);

        // Warranty
        Gate::define('view-any-warranty', [WarrantyPolicy::class, 'viewAny']);
        Gate::define('view-any-employee-warranty', [WarrantyPolicy::class, 'viewAnyEmployee']);
        Gate::define('view-warranty', [WarrantyPolicy::class, 'view']);
        Gate::define('create-warranty', [WarrantyPolicy::class, 'create']);
        Gate::define('edit-warranty', [WarrantyPolicy::class, 'update']);
        Gate::define('delete-warranty', [WarrantyPolicy::class, 'delete']);
        Gate::define('restore-warranty', [WarrantyPolicy::class, 'restore']);

        // Warranty Work
        Gate::define('attach-warranty', [WarrantyWorkPolicy::class, 'attach']);
        Gate::define('detach-warranty', [WarrantyWorkPolicy::class, 'detach']);

        // Execute Work
        Gate::define('view-any-execute-works', [
            ExecuteWorkPolicy::class,
            'viewAny',
        ]);
        Gate::define('execute-work', [ExecuteWorkPolicy::class, 'execute']);
        Gate::define('mark-unfinish-execute-work', [
            ExecuteWorkPolicy::class,
            'markUnfinish',
        ]);
        Gate::define('mark-finish-execute-work', [
            ExecuteWorkPolicy::class,
            'markFinish',
        ]);
        Gate::define('make-continuation-execute-work', [
            ExecuteWorkPolicy::class,
            'makeContinuation',
        ]);
        Gate::define('delete-execute-work', [
            ExecuteWorkPolicy::class,
            'delete',
        ]);
        Gate::define('restore-execute-work', [
            ExecuteWorkPolicy::class,
            'restore',
        ]);

        // Execute Work Photo
        Gate::define('view-any-execute-work-photo', [
            ExecuteWorkPhotoPolicy::class,
            'viewAny',
        ]);
        Gate::define('upload-execute-work-photo', [
            ExecuteWorkPhotoPolicy::class,
            'upload',
        ]);
        Gate::define('delete-execute-work-photo', [
            ExecuteWorkPhotoPolicy::class,
            'delete',
        ]);

        // Address
        Gate::define('view-any-address', [AddressPolicy::class, 'viewAny']);
        Gate::define('view-any-employee-address', [
            AddressPolicy::class,
            'viewAnyEmployee',
        ]);
        Gate::define('pro6pp-autocomplete-address', [AddressPolicy::class, 'pro6ppAutocompleteAddress']);
        Gate::define('create-address', [AddressPolicy::class, 'create']);
        Gate::define('view-address', [AddressPolicy::class, 'view']);
        Gate::define('edit-address', [AddressPolicy::class, 'update']);
        Gate::define('delete-address', [AddressPolicy::class, 'delete']);
        Gate::define('restore-address', [AddressPolicy::class, 'restore']);
        Gate::define('force-delete-address', [
            AddressPolicy::class,
            'forceDelete',
        ]);

        // Employee
        Gate::define('view-any-employee', [EmployeePolicy::class, 'viewAny']);
        Gate::define('create-employee', [EmployeePolicy::class, 'create']);
        Gate::define('edit-employee', [EmployeePolicy::class, 'update']);
        Gate::define('set-image-employee', [EmployeePolicy::class, 'setImage']);
        Gate::define('view-employee', [EmployeePolicy::class, 'view']);
        Gate::define('delete-employee', [EmployeePolicy::class, 'delete']);
        Gate::define('restore-employee', [EmployeePolicy::class, 'restore']);
        Gate::define('force-delete-employee', [
            EmployeePolicy::class,
            'forceDelete',
        ]);

        // Employee Invitation
        Gate::define('view-any-pending-invitation-employee', [EmployeeInvitationPolicy::class, 'viewAny']);
        Gate::define('view-invitation-employee', [EmployeeInvitationPolicy::class, 'view']);
        Gate::define('cancel-invitation-employee', [EmployeeInvitationPolicy::class, 'cancel']);
        Gate::define('send-invitation-employee', [EmployeeInvitationPolicy::class, 'store']);

        // Customer
        Gate::define('view-any-customer', [CustomerPolicy::class, 'viewAny']);
        Gate::define('create-customer', [CustomerPolicy::class, 'create']);
        Gate::define('view-customer', [CustomerPolicy::class, 'view']);
        Gate::define('edit-customer', [CustomerPolicy::class, 'update']);
        Gate::define('delete-customer', [CustomerPolicy::class, 'delete']);
        Gate::define('restore-customer', [CustomerPolicy::class, 'restore']);
        Gate::define('restore-customer', [CustomerPolicy::class, 'restore']);
        Gate::define('view-city-of-customer', [CustomerPolicy::class, 'viewCityOfCustomer']);
        Gate::define('force-delete-customer', [
            CustomerPolicy::class,
            'forceDelete',
        ]);

        // Permission
        Gate::define('view-any-permission', [PermissionPolicy::class, 'viewAny']);

        // Car
        Gate::define('view-any-car', [CarPolicy::class, 'viewAny']);
        Gate::define('view-car', [CarPolicy::class, 'view']);
        Gate::define('create-car', [CarPolicy::class, 'create']);
        Gate::define('set-image-car', [CarPolicy::class, 'setImage']);
        Gate::define('edit-car', [CarPolicy::class, 'edit']);
        Gate::define('delete-car', [CarPolicy::class, 'delete']);
        Gate::define('restore-car', [CarPolicy::class, 'restore']);
        Gate::define('force-delete-car', [CarPolicy::class, 'forceDelete']);

        // Work Service
        Gate::define('view-any-work-service', [
            WorkServicePolicy::class,
            'viewAny',
        ]);
        Gate::define('view-any-workservice', [ // alias of "view-any-work-service"
            WorkServicePolicy::class,
            'viewAny',
        ]);
        Gate::define('view-work-service', [WorkServicePolicy::class, 'view']);
        Gate::define('create-work-service', [
            WorkServicePolicy::class,
            'create',
        ]);
        Gate::define('edit-work-service', [WorkServicePolicy::class, 'edit']);
        Gate::define('delete-work-service', [
            WorkServicePolicy::class,
            'delete',
        ]);
        Gate::define('restore-work-service', [
            WorkServicePolicy::class,
            'restore',
        ]);
        Gate::define('force-delete-work-service', [
            WorkServicePolicy::class,
            'forceDelete',
        ]);

        // Car Register Time
        Gate::define('view-any-car-register-time', [
            CarRegisterTimePolicy::class,
            'viewAny',
        ]);
        Gate::define('view-car-register-time', [
            CarRegisterTimePolicy::class,
            'view',
        ]);
        Gate::define('register-car-time', [
            CarRegisterTimePolicy::class,
            'register',
        ]);
        Gate::define('register-worklist-car-time', [
            CarRegisterTimePolicy::class,
            'registerToWorklist',
        ]);
        Gate::define('create-car-register-time', [
            CarRegisterTimePolicy::class,
            'create',
        ]);
        Gate::define('mark-out-car-register-time', [
            CarRegisterTimePolicy::class,
            'markOut',
        ]);
        Gate::define('mark-return-car-register-time', [
            CarRegisterTimePolicy::class,
            'markReturn',
        ]);
        Gate::define('edit-car-register-time', [
            CarRegisterTimePolicy::class,
            'update',
        ]);
        Gate::define('delete-car-register-time', [
            CarRegisterTimePolicy::class,
            'delete',
        ]);
        Gate::define('restore-car-register-time', [
            CarRegisterTimePolicy::class,
            'restore',
        ]);
        Gate::define('force-delete-car-register-time', [
            CarRegisterTimePolicy::class,
            'forceDelete',
        ]);

        // Car Register Time Employee
        Gate::define('view-any-car-register-time-employee', [
            CarRegisterTimeEmployeePolicy::class,
            'viewAny',
        ]);
        Gate::define('view-car-register-time-employee', [
            CarRegisterTimeEmployeePolicy::class,
            'view',
        ]);
        Gate::define('assign-car-register-time-employee', [
            CarRegisterTimeEmployeePolicy::class,
            'assign',
        ]);
        Gate::define('set-as-driver-car-register-time-employee', [
            CarRegisterTimeEmployeePolicy::class,
            'setAsDriver',
        ]);
        Gate::define('set-out-car-register-time-employee', [
            CarRegisterTimeEmployeePolicy::class,
            'setOut',
        ]);
        Gate::define('unassign-car-register-time-employee', [
            CarRegisterTimeEmployeePolicy::class,
            'unassign',
        ]);

        // Workday
        Gate::define('view-any-workday', [WorkdayPolicy::class, 'viewAny']);
        Gate::define('view-trashed-workday', [
            WorkdayPolicy::class,
            'viewTrasheds',
        ]);
        Gate::define('view-workday', [WorkdayPolicy::class, 'view']);
        Gate::define('attach-appointment-workday', [
            WorkdayPolicy::class,
            'attachAppointment',
        ]);
        Gate::define('attach-many-appointments-workday', [
            WorkdayPolicy::class,
            'attachManyAppointments',
        ]);
        Gate::define('detach-appointment-workday', [
            WorkdayPolicy::class,
            'detachAppointment',
        ]);
        Gate::define('detach-many-appointments-workday', [
            WorkdayPolicy::class,
            'detachManyAppointments',
        ]);
        Gate::define('truncate-appointments-workday', [
            WorkdayPolicy::class,
            'truncateAppointments',
        ]);
        Gate::define('process-workday', [WorkdayPolicy::class, 'process']);
        Gate::define('calculate-workday', [WorkdayPolicy::class, 'calculate']);
        Gate::define('delete-workday', [WorkdayPolicy::class, 'delete']);
        Gate::define('restore-workday', [WorkdayPolicy::class, 'restore']);

        // Worklist
        Gate::define('view-any-worklist', [WorklistPolicy::class, 'viewAny']);
        Gate::define('view-any-worklist-workday', [
            WorklistPolicy::class,
            'viewAnyWorkday',
        ]);
        Gate::define('view-any-employee-worklist', [
            WorklistPolicy::class,
            'viewAnyEmployee',
        ]);
        Gate::define('view-worklist', [WorklistPolicy::class, 'view']);
        Gate::define('create-worklist', [WorklistPolicy::class, 'create']);
        Gate::define('attach-appointment-worklist', [
            WorklistPolicy::class,
            'attachAppointment',
        ]);
        Gate::define('attach-many-appointments-worklist', [
            WorklistPolicy::class,
            'attachManyAppointments',
        ]);
        Gate::define('detach-appointment-worklist', [
            WorklistPolicy::class,
            'detachAppointment',
        ]);
        Gate::define('detach-many-appointments-worklist', [
            WorklistPolicy::class,
            'detachManyAppointments',
        ]);
        Gate::define('truncate-appointments-worklist', [
            WorklistPolicy::class,
            'truncateAppointments',
        ]);
        Gate::define('edit-worklist', [WorklistPolicy::class, 'update']);
        Gate::define('process-worklist', [WorklistPolicy::class, 'process']);
        Gate::define('calculate-worklist', [
            WorklistPolicy::class,
            'calculate',
        ]);
        Gate::define('delete-worklist', [WorklistPolicy::class, 'delete']);
        Gate::define('restore-worklist', [WorklistPolicy::class, 'restore']);
        Gate::define('move-appointment-worklist', [WorklistPolicy::class, 'moveAppointment']);
        Gate::define('force-delete-worklist', [
            WorklistPolicy::class,
            'forceDelete',
        ]);
        Gate::define('sorting-route-worklist', [
            WorklistPolicy::class,
            'sortingRoute',
        ]);

        // Work contractt
        Gate::define('view-any-work-contract', [WorkContractPolicy::class, 'viewAny']);
        Gate::define('view-work-contract', [WorkContractPolicy::class, 'view',]);
        Gate::define('create-work-contract', [WorkContractPolicy::class, 'create']);
        Gate::define('draft-work-contract', [WorkContractPolicy::class, 'draft']);
        Gate::define('send-work-contract', [WorkContractPolicy::class, 'send']);
        Gate::define('print-work-contract', [WorkContractPolicy::class, 'print']);
        Gate::define('resend-work-contract', [WorkContractPolicy::class, 'send']);
        Gate::define('edit-work-contract', [WorkContractPolicy::class, 'update']);
        Gate::define('delete-work-contract', [WorkContractPolicy::class, 'delete']);
        Gate::define('nullify-work-contract', [WorkContractPolicy::class, 'nullify']);
        Gate::define('restore-work-contract', [WorkContractPolicy::class, 'restore']);
        Gate::define('force-delete-work-contract', [WorkContractPolicy::class, 'forceDelete']);
        Gate::define('set-default-work-contract', [WorkContractPolicy::class, 'setAsDefaultSetting']);
        Gate::define('upload-signed-document-work-contract', [WorkContractPolicy::class, 'uploadSignedDoc']);
        Gate::define('remove-signed-document-work-contract', [WorkContractPolicy::class, 'removeSignedDoc']);
        Gate::define('apply-company-format-work-contract', [WorkContractPolicy::class, 'applyCompanyFormat']);

        // Log History
        Gate::define('view-any-log', [LogPolicy::class, 'viewAny']);
        Gate::define('restore-log', [LogPolicy::class, 'restore']);
        Gate::define('restore-many-log', [LogPolicy::class, 'restoreMany']);
        Gate::define('delete-log', [LogPolicy::class, 'delete']);
        Gate::define('delete-many-log', [LogPolicy::class, 'deleteMany']);
        Gate::define('force-delete-log', [LogPolicy::class, 'forceDelete']);
        Gate::define('force-delete-many-log', [LogPolicy::class, 'forceDeleteMany']);

        // Post It
        Gate::define('view-any-post-it', [PostItPolicy::class, 'viewAny']);
        Gate::define('view-post-it', [PostItPolicy::class, 'view']);
        Gate::define('create-post-it', [PostItPolicy::class, 'create']);
        Gate::define('edit-post-it', [PostItPolicy::class, 'update']);
        Gate::define('assign-user-post-it', [
            PostItPolicy::class,
            'assignUser',
        ]);
        Gate::define('unassign-user-post-it', [
            PostItPolicy::class,
            'unassignUser',
        ]);
        Gate::define('delete-post-it', [PostItPolicy::class, 'delete']);
        Gate::define('restore-post-it', [PostItPolicy::class, 'restore']);
        Gate::define('force-delete-post-it', [
            PostItPolicy::class,
            'forceDelete',
        ]);

        // Payment Pickup
        Gate::define('view-any-payment-pickup', [
            PaymentPickupPolicy::class,
            'viewAny',
        ]);
        Gate::define('view-appointment-payment-pickup', [
            PaymentPickupPolicy::class,
            'viewAppointment',
        ]);
        Gate::define('view-payment-pickup', [
            PaymentPickupPolicy::class,
            'view',
        ]);
        Gate::define('create-payment-pickup', [
            PaymentPickupPolicy::class,
            'create',
        ]);
        Gate::define('edit-payment-pickup', [
            PaymentPickupPolicy::class,
            'update',
        ]);
        Gate::define('add-pickupable-payment-pickup', [
            PaymentPickupPolicy::class,
            'addPickupable',
        ]);
        Gate::define('add-multiple-pickupable-payment-pickup', [
            PaymentPickupPolicy::class,
            'addMultiplePickupables',
        ]);
        Gate::define('remove-pickupable-payment-pickup', [
            PaymentPickupPolicy::class,
            'removePickupable',
        ]);
        Gate::define('remove-multiple-pickupable-payment-pickup', [
            PaymentPickupPolicy::class,
            'removeMultiplePickupable',
        ]);
        Gate::define('delete-payment-pickup', [
            PaymentPickupPolicy::class,
            'delete',
        ]);
        Gate::define('restore-payment-pickup', [
            PaymentPickupPolicy::class,
            'restore',
        ]);
        Gate::define('force-delete-payment-pickup', [
            PaymentPickupPolicy::class,
            'forceDelete',
        ]);


        // Owner
        Gate::define('view-any-owner', [OwnerPolicy::class, 'viewAny']);
        Gate::define('view-owner', [OwnerPolicy::class, 'view']);
        Gate::define('create-owner', [OwnerPolicy::class, 'create']);
        Gate::define('edit-owner', [OwnerPolicy::class, 'update']);
        Gate::define('delete-owner', [OwnerPolicy::class, 'delete']);
        Gate::define('restore-owner', [OwnerPolicy::class, 'restore']);
        Gate::define('force-delete-owner', [OwnerPolicy::class, 'forceDelete']);

        // Owner invitations
        Gate::define('view-any-invitation-owner', [OwnerInvitationPolicy::class, 'viewAny']);
        Gate::define('view-invitation-owner', [OwnerInvitationPolicy::class, 'view']);
        Gate::define('cancel-invitation-owner', [OwnerInvitationPolicy::class, 'cancel']);
        Gate::define('send-invitation-owner', [OwnerInvitationPolicy::class, 'store']);

        // Setting
        Gate::define('view-any-setting', [SettingPolicy::class, 'viewAny']);
        Gate::define('view-setting', [SettingPolicy::class, 'view']);
        Gate::define('edit-setting', [SettingPolicy::class, 'edit']);

        // FAQ
        Gate::define('view-any-frequentlyaskedquestion', [FAQPolicy::class, 'viewAny']);
        Gate::define('view-any-faq', [FAQPolicy::class, 'viewAny']); // alias of "view-any-frequentlyaskedquestion"
        Gate::define('view-faq', [FAQPolicy::class, 'viewFaq']);

        // Help Desk
        Gate::define('view-any-help-desk', [HelpDeskPolicy::class, 'viewAny']);
        Gate::define('view-help-desk', [HelpDeskPolicy::class, 'view']);
        Gate::define('create-help-desk', [HelpDeskPolicy::class, 'create']);
        Gate::define('update-help-desk', [HelpDeskPolicy::class, 'update']);
        Gate::define('delete-help-desk', [HelpDeskPolicy::class, 'delete']);

        // Customer note
        Gate::define('view-any-customer-note', [CustomerNotePolicy::class, 'viewAny']);
        Gate::define('view-customer-note', [CustomerNotePolicy::class, 'view']);
        Gate::define('create-customer-note', [CustomerNotePolicy::class, 'create']);
        Gate::define('edit-customer-note', [CustomerNotePolicy::class, 'edit']);
        Gate::define('delete-customer-note', [CustomerNotePolicy::class, 'delete']);
        Gate::define('restore-customer-note', [CustomerNotePolicy::class, 'restore']);
        Gate::define('force-delete-customer-note', [CustomerNotePolicy::class, 'forceDelete']);
    }
}
