<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\Policies\{
    CarPolicy,
    CostPolicy,
    WorkPolicy,
    OwnerPolicy,
    AddressPolicy,
    InvoicePolicy,
    ReceiptPolicy,
    WorkdayPolicy,
    RevenuePolicy,
    WorklistPolicy,
    WarrantyPolicy,
    CustomerPolicy,
    EmployeePolicy,
    QuotationPolicy,
    AppointmentPolicy,
    ExecuteWorkPolicy,
    InvoiceItemPolicy,
    PaymentTermPolicy,
    SubAppointmentPolicy,
    ExecuteWorkPhotoPolicy,
    AppointmentWorkerPolicy,
    RegisterInvitationPolicy
};

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            return $user->hasRole('admin') ? true : null;
        });

        // Appointment
        Gate::define('view-any-appointment', [AppointmentPolicy::class, 'viewAny']);
        Gate::define('view-appointment', [AppointmentPolicy::class, 'view']);
        Gate::define('create-appointment', [AppointmentPolicy::class, 'create']);
        Gate::define('generate-invoice-appointment', [AppointmentPolicy::class, 'generateInvoice']);
        Gate::define('update-appointment', [AppointmentPolicy::class, 'update']);
        Gate::define('cancel-appointment', [AppointmentPolicy::class, 'cancel']);
        Gate::define('assign-appointment-employee', [AppointmentPolicy::class, 'assignEmployee']);
        Gate::define('unassign-appointment-employee', [AppointmentPolicy::class, 'unassignEmployee']);
        Gate::define('execute-appointment', [AppointmentPolicy::class, 'execute']);
        Gate::define('process-appointment', [AppointmentPolicy::class, 'process']);
        Gate::define('calculate-appointment', [AppointmentPolicy::class, 'calculate']);
        Gate::define('reschedule-appointment', [AppointmentPolicy::class, 'reschedule']);
        Gate::define('delete-appointment', [AppointmentPolicy::class, 'delete']);
        Gate::define('restore-appointment', [AppointmentPolicy::class, 'restore']);
        Gate::define('force-delete-appointment', [AppointmentPolicy::class, 'forceDelete']);

        // Sub Appointment
        Gate::define('view-any-sub-appointment', [SubAppointmentPolicy::class, 'viewAny']);
        Gate::define('view-sub-appointment', [SubAppointmentPolicy::class, 'view']);
        Gate::define('create-sub-appointment', [SubAppointmentPolicy::class, 'create']);
        Gate::define('edit-sub-appointment', [SubAppointmentPolicy::class, 'update']);
        Gate::define('process-sub-appointment', [SubAppointmentPolicy::class, 'process']);
        Gate::define('execute-sub-appointment', [SubAppointmentPolicy::class, 'execute']);
        Gate::define('cancel-sub-appointment', [SubAppointmentPolicy::class, 'cancel']);
        Gate::define('reschedule-sub-appointment', [SubAppointmentPolicy::class, 'reschedule']);
        Gate::define('delete-sub-appointment', [SubAppointmentPolicy::class, 'delete']);
        Gate::define('restore-sub-appointment', [SubAppointmentPolicy::class, 'restore']);
        Gate::define('force-delete-sub-appointment', [SubAppointmentPolicy::class, 'forceDelete']);

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
        Gate::define('force-delete-revenue', [RevenuePolicy::class, 'forceDelete']);

        // Receipts
        Gate::define('view-any-receipt', [ReceiptPolicy::class, 'viewAny']);
        Gate::define('view-receipt', [ReceiptPolicy::class, 'view']);
        Gate::define('create-receipt', [ReceiptPolicy::class, 'create']);
        Gate::define('edit-receipt', [ReceiptPolicy::class, 'update']);
        Gate::define('delete-receipt', [ReceiptPolicy::class, 'delete']);
        Gate::define('restore-receipt', [ReceiptPolicy::class, 'restore']);
        Gate::define('force-delete-receipt', [ReceiptPolicy::class, 'forceDelete']);

        // Quotation
        Gate::define('view-any-quotation', [QuotationPolicy::class, 'viewAny']);
        Gate::define('view-any-customer-quotation', [QuotationPolicy::class, 'viewAnyCustomer']);
        Gate::define('view-quotation', [QuotationPolicy::class, 'view']);
        Gate::define('create-quotation', [QuotationPolicy::class, 'create']);
        Gate::define('create-quotation-with-appointment', [QuotationPolicy::class, 'createWithAppointment']);
        Gate::define('update-quotation', [QuotationPolicy::class, 'update']);
        Gate::define('send-quotation', [QuotationPolicy::class, 'send']);
        Gate::define('print-quotation', [QuotationPolicy::class, 'print']);
        Gate::define('revise-quotation', [QuotationPolicy::class, 'revise']);
        Gate::define('honor-quotation', [QuotationPolicy::class, 'honor']);
        Gate::define('cancel-quotation', [QuotationPolicy::class, 'cancel']);
        Gate::define('delete-quotation', [QuotationPolicy::class, 'delete']);
        Gate::define('restore-quotation', [QuotationPolicy::class, 'restore']);
        Gate::define('force-delete-quotation', [QuotationPolicy::class, 'forceDelete']);
        Gate::define('add-quotation-attachment', [QuotationPolicy::class, 'addAttachment']);
        Gate::define('remove-quotation-attachment', [QuotationPolicy::class, 'removeAttachment']);

        // Invoices
        Gate::define('view-any-invoice', [InvoicePolicy::class, 'viewAny']);
        Gate::define('view-any-appointment-invoice', [InvoicePolicy::class, 'viewAnyAppointment']);
        Gate::define('view-invoice', [InvoicePolicy::class, 'view']);
        Gate::define('create-invoice', [InvoicePolicy::class, 'create']);
        Gate::define('update-invoice', [InvoicePolicy::class, 'update']);
        Gate::define('delete-invoice', [InvoicePolicy::class, 'delete']);
        Gate::define('restore-invoice', [InvoicePolicy::class, 'restore']);
        Gate::define('force-delete-invoice', [InvoicePolicy::class, 'forceDelete']);

        // Invoice Items
        Gate::define('view-any-invoice-item', [InvoiceItemPolicy::class, 'viewAny']);
        Gate::define('view-any-appointment-invoice-item', [InvoiceItemPolicy::class, 'viewAnyAppointment']);
        Gate::define('view-invoice-item', [InvoiceItemPolicy::class, 'view']);
        Gate::define('create-invoice-item', [InvoiceItemPolicy::class, 'create']);
        Gate::define('update-invoice-item', [InvoiceItemPolicy::class, 'update']);
        Gate::define('delete-invoice-item', [InvoiceItemPolicy::class, 'delete']);
        Gate::define('restore-invoice-item', [InvoiceItemPolicy::class, 'restore']);
        Gate::define('force-delete-invoice-item', [InvoiceItemPolicy::class, 'forceDelete']);

        // Payment Term
        Gate::define('view-any-payment-term', [PaymentTermPolicy::class, 'viewAny']);
        Gate::define('view-any-appointment-payment-term', [PaymentTermPolicy::class, 'viewAnyAppointment']);
        Gate::define('view-payment-term', [PaymentTermPolicy::class, 'view']);
        Gate::define('create-payment-term', [PaymentTermPolicy::class, 'create']);
        Gate::define('update-payment-term', [PaymentTermPolicy::class, 'update']);
        Gate::define('delete-payment-term', [PaymentTermPolicy::class, 'delete']);
        Gate::define('restore-payment-term', [PaymentTermPolicy::class, 'restore']);
        Gate::define('force-delete-payment-term', [PaymentTermPolicy::class, 'forceDelete']);

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

        // Warranty
        Gate::define('view-any-warranty', [WarrantyPolicy::class, 'viewAny']);
        Gate::define('view-warranty', [WarrantyPolicy::class, 'view']);
        Gate::define('create-warranty', [WarrantyPolicy::class, 'create']);
        Gate::define('edit-warranty', [WarrantyPolicy::class, 'update']);
        Gate::define('delete-warranty', [WarrantyPolicy::class, 'delete']);
        Gate::define('restore-warranty', [WarrantyPolicy::class, 'restore']);

        // Execute Work
        Gate::define('view-any-execute-works', [ExecuteWorkPolicy::class, 'viewAny']);
        Gate::define('execute-work', [ExecuteWorkPolicy::class, 'execute']);
        Gate::define('mark-unfinish-execute-work', [ExecuteWorkPolicy::class, 'markUnfinish']);
        Gate::define('mark-finish-execute-work', [ExecuteWorkPolicy::class, 'markFinish']);
        Gate::define('make-continuation-execute-work', [ExecuteWorkPolicy::class, 'makeContinuation']);
        Gate::define('delete-execute-work', [ExecuteWorkPolicy::class, 'delete']);
        Gate::define('restore-execute-work', [ExecuteWorkPolicy::class, 'restore']);

        // Execute Work Photo
        Gate::define('view-any-execute-work-photo', [ExecuteWorkPhotoPolicy::class, 'viewAny']);
        Gate::define('upload-execute-work-photo', [ExecuteWorkPhotoPolicy::class, 'upload']);
        Gate::define('delete-execute-work-photo', [ExecuteWorkPhotoPolicy::class, 'delete']);

        // Address
        Gate::define('view-any-address', [AddressPolicy::class, 'viewAny']);
        Gate::define('view-any-employee-address', [AddressPolicy::class, 'viewAnyEmployee']);
        Gate::define('create-address', [AddressPolicy::class, 'create']);
        Gate::define('edit-address', [AddressPolicy::class, 'update']);
        Gate::define('delete-address', [AddressPolicy::class, 'delete']);
        Gate::define('restore-address', [AddressPolicy::class, 'restore']);
        Gate::define('force-delete-address', [AddressPolicy::class, 'forceDelete']);

        // Employee
        Gate::define('view-any-employee', [EmployeePolicy::class, 'viewAny']);
        Gate::define('create-employee', [EmployeePolicy::class, 'create']);
        Gate::define('edit-employee', [EmployeePolicy::class, 'update']);
        Gate::define('view-employee', [EmployeePolicy::class, 'view']);
        Gate::define('delete-employee', [EmployeePolicy::class, 'delete']);
        Gate::define('restore-employee', [EmployeePolicy::class, 'restore']);
        Gate::define('force-delete-employee', [EmployeePolicy::class, 'forceDelete']);

        // Customer
        Gate::define('view-any-customer', [CustomerPolicy::class, 'viewAny']);
        Gate::define('create-customer', [CustomerPolicy::class, 'create']);
        Gate::define('view-customer', [CustomerPolicy::class, 'view']);
        Gate::define('edit-customer', [CustomerPolicy::class, 'update']);
        Gate::define('delete-customer', [CustomerPolicy::class, 'delete']);
        Gate::define('restore-customer', [CustomerPolicy::class, 'restore']);
        Gate::define('force-delete-customer', [CustomerPolicy::class, 'forceDelete']);

        // Car
        Gate::define('view-any-car', [CarPolicy::class, 'viewAny']);
        Gate::define('view-car', [CarPolicy::class, 'view']);
        Gate::define('create-car', [CarPolicy::class, 'create']);
        Gate::define('set-image-car', [CarPolicy::class, 'setImage']);
        Gate::define('edit-car', [CarPolicy::class, 'edit']);
        Gate::define('delete-car', [CarPolicy::class, 'delete']);
        Gate::define('restore-car', [CarPolicy::class, 'restore']);
        Gate::define('force-delete-car', [CarPolicy::class, 'forceDelete']);

        // Workday
        Gate::define('view-any-workday', [WorkdayPolicy::class, 'viewAny']);
        Gate::define('view-workday', [WorkdayPolicy::class, 'view']);
        Gate::define('attach-appointment-workday', [WorkdayPolicy::class, 'attachAppointment']);
        Gate::define('attach-many-appointments-workday', [WorkdayPolicy::class, 'attachManyAppointments']);
        Gate::define('detach-appointment-workday', [WorkdayPolicy::class, 'detachAppointment']);
        Gate::define('detach-many-appointments-workday', [WorkdayPolicy::class, 'detachManyAppointments']);
        Gate::define('truncate-appointments-workday', [WorkdayPolicy::class, 'truncateAppointments']);
        Gate::define('process-workday', [WorkdayPolicy::class, 'process']);
        Gate::define('calculate-workday', [WorkdayPolicy::class, 'calculate']);
    
        // Worklist
        Gate::define('view-any-worklist', [WorklistPolicy::class, 'viewAny']);
        Gate::define('view-any-worklist-workday', [WorklistPolicy::class, 'viewAnyWorkday']);
        Gate::define('view-worklist', [WorklistPolicy::class, 'view']);
        Gate::define('create-worklist', [WorklistPolicy::class, 'create']);
        Gate::define('attach-appointment-worklist', [WorklistPolicy::class, 'attachAppointment']);
        Gate::define('attach-many-appointments-worklist', [WorklistPolicy::class, 'attachManyAppointments']);
        Gate::define('detach-appointment-worklist', [WorklistPolicy::class, 'detachAppointment']);
        Gate::define('detach-many-appointments-worklist', [WorklistPolicy::class, 'detachManyAppointments']);
        Gate::define('truncate-appointments-worklist', [WorklistPolicy::class, 'truncateAppointments']);
        Gate::define('edit-worklist', [WorklistPolicy::class, 'update']);
        Gate::define('process-worklist', [WorklistPolicy::class, 'process']);
        Gate::define('calculate-worklist', [WorklistPolicy::class, 'calculate']);
        Gate::define('delete-worklist', [WorklistPolicy::class, 'delete']);
        Gate::define('force-delete-worklist', [WorklistPolicy::class, 'forceDelete']);

        // Register Invitation
        Gate::define('send-employee-register-invitation', [RegisterInvitationPolicy::class, 'sendEmployeeRegisterInvitation']);
        Gate::define('send-owner-register-invitation', [RegisterInvitationPolicy::class, 'sendOwnerRegisterInvitation']);

        // Owner
        Gate::define('view-any-owner', [OwnerPolicy::class, 'viewAny']);
        Gate::define('view-owner', [OwnerPolicy::class, 'view']);
        Gate::define('create-owner', [OwnerPolicy::class, 'create']);
        Gate::define('edit-owner', [OwnerPolicy::class, 'update']);
        Gate::define('delete-owner', [OwnerPolicy::class, 'delete']);
        Gate::define('restore-owner', [OwnerPolicy::class, 'restore']);
        Gate::define('force-delete-owner', [OwnerPolicy::class, 'forceDelete']);
    }
}
