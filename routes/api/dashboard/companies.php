<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Company\{
	Car\CarController,
		Car\CarRegisterTimeController,
		Car\CarRegisterTimeEmployeeController,

	InvoiceController,
		InvoiceItemController,
	CompanyController,
	
	Addresses\AddressController,
	Addresses\CustomerAddressController,
	Addresses\EmployeeAddressController,
	Addresses\OwnerAddressController,

	EmployeeController,
	CustomerController,
	QuotationController,
		Works\QuotationWorkController,
	OwnerController,
	InspectorController,
	PaymentTermController,
	PaymentPickupController,
	WorkdayController,
		WorkdayWorklistController,
		Costs\WorkdayCostController,
		WorklistController,
			Costs\WorklistCostController,
			WorklistAppointmentController,
			AppointmentController,
				SubAppointmentController,
					Works\SubAppointmentWorkController,
				AppointmentEmployeeController,
				Costs\AppointmentCostController,
				Works\AppointmentWorkController,
	CostController,
		Receipts\CostReceiptController,
	RevenueController,
		Receipts\RevenueReceiptController,
	ReceiptController,
	RegisterInvitationController,
	WorkController,
	WarrantyController,
	WorkContractController,
	ExecuteWorkController,
		ExecuteWorkPhotoController,
	PostItController,
	AnalyticController
};

Route::post('register', [CompanyController::class, 'register']);

Route::group(['middleware' => ['has_company']], function () {
	Route::get('user', [CompanyController::class, 'userCompany']);
	Route::get('settings', [CompanyController::class, 'settings']);
	Route::post('upload_logo', [CompanyController::class, 'uploadCompanyLogo']);
	Route::match(['PUT', 'PATCH'], 'update', [CompanyController::class, 'update']);

	/**
	 * Company Owners Module
	 */
	Route::group(['prefix' => 'owners'], function () {
		Route::get('/', [OwnerController::class, 'companyOwners']);
		Route::get('inviteables', [OwnerController::class, 'inviteableOwners']);
		Route::get('trasheds', [OwnerController::class, 'trashedOwners']);
		Route::post('store', [OwnerController::class, 'store']);
		Route::get('view', [OwnerController::class, 'view']);
		Route::match(['PUT', 'PATCH'], 'update', [OwnerController::class, 'update']);
		Route::delete('delete', [OwnerController::class, 'delete']);
		Route::patch('restore', [OwnerController::class, 'restore']);
	});

	/**
	 * Company Employee Module
	 */
	Route::group(['prefix' => 'employees'], function () {
		Route::get('/', [EmployeeController::class, 'companyEmployees']);
		Route::get('inviteables', [EmployeeController::class, 'inviteableEmployees']);
		Route::get('trasheds', [EmployeeController::class, 'trashedEmployees']);
		Route::post('store', [EmployeeController::class, 'store']);
		Route::get('view', [EmployeeController::class, 'view']);
		Route::match(['PUT', 'PATCH'], 'update', [EmployeeController::class, 'update']);
		Route::delete('delete', [EmployeeController::class, 'delete']);
		Route::patch('restore', [EmployeeController::class, 'restore']);
	});

	/**
	 * Company Customer Module
	 */
	Route::group(['prefix' => 'customers'], function () {
		Route::get('/', [CustomerController::class, 'companyCustomers']);
		Route::get('trasheds', [CustomerController::class, 'trashedCustomers']);
		Route::post('store', [CustomerController::class, 'store']);
		Route::get('view', [CustomerController::class, 'view']);
		Route::match(['PUT', 'PATCH'], 'update', [CustomerController::class, 'update']);
		Route::delete('delete', [CustomerController::class, 'delete']);
		Route::patch('restore', [CustomerController::class, 'restore']);

		Route::get('appointments', [AppointmentController::class, 'customerAppointments']);
		Route::get('quotations', [QuotationController::class, 'customerQuotations']);
	});

	/**
	 * Company Workday Module
	 */
	Route::group(['prefix' => 'workdays'], function () {
		Route::get('/', [WorkdayController::class, 'companyWorkdays']);
		Route::get('current', [WorkdayController::class, 'currentWorkday']);
		Route::get('view', [WorkdayController::class, 'view']);
		Route::post('process', [WorkdayController::class, 'process']);
		Route::post('calculate', [WorkdayController::class, 'calculate']);

		Route::group(['prefix' => 'costs'], function () {
			Route::get('/', [WorkdayCostController::class, 'workdayCosts']);
			Route::post('store_record', [WorkdayCostController::class, 'storeRecord']);
			Route::post('record', [WorkdayCostController::class, 'record']);
			Route::post('record_many', [WorkdayCostController::class, 'recordMany']);
			Route::post('unrecord', [WorkdayCostController::class, 'unrecord']);
			Route::post('unrecord_many', [WorkdayCostController::class, 'unrecordMany']);
			Route::post('truncate', [WorkdayCostController::class, 'truncate']);
		});
	});

	/**
	 * Company Worklist Module
	 */
	Route::group(['prefix' => 'worklists'], function () {
		Route::get('/', [WorklistController::class, 'companyWorklists']);
		Route::get('/of_workday', [WorklistController::class, 'workdayWorklists']);
		Route::get('trasheds', [WorklistController::class, 'trashedWorklists']);
		Route::post('store', [WorklistController::class, 'store']);
		Route::get('view', [WorklistController::class, 'view']);
		Route::post('process', [WorklistController::class, 'process']);
		Route::post('calculate', [WorklistController::class, 'calculate']);
		Route::match(['PUT', 'PATCH'], 'update', [WorklistController::class, 'update']);
		Route::delete('delete', [WorklistController::class, 'delete']);
		Route::patch('restore', [WorklistController::class, 'restore']);

		/**
		 * Worklist Cost Module
		 */
		Route::group(['prefix' => 'costs'], function () {
			Route::get('/', [WorklistCostController::class, 'worklistCosts']);
			Route::post('store_record', [WorklistCostController::class, 'storeRecord']);
			Route::post('record', [WorklistCostController::class, 'record']);
			Route::post('record_many', [WorklistCostController::class, 'recordMany']);
			Route::post('unrecord', [WorklistCostController::class, 'unrecord']);
			Route::post('unrecord_many', [WorklistCostController::class, 'unrecordMany']);
			Route::post('truncate', [WorklistCostController::class, 'truncate']);
		});

		/**
		 * Worklist Appointment Module
		 */
		Route::group(['prefix' => 'appointments'], function () {
			Route::get('/', [WorklistAppointmentController::class, 'worklistAppointments']);
			Route::post('attach', [WorklistAppointmentController::class, 'attach']);
			Route::post('attach_many', [WorklistAppointmentController::class, 'attachMany']);
			Route::post('move', [WorklistAppointmentController::class, 'move']);
			Route::post('detach', [WorklistAppointmentController::class, 'detach']);
			Route::post('detach_many', [WorklistAppointmentController::class, 'detachMany']);
			Route::post('truncate', [WorklistAppointmentController::class, 'truncate']);
		});
	});

	/**
	 * Company Appointment Module
	 */
	Route::group(['prefix' => 'appointments'], function () {
		Route::get('/', [AppointmentController::class, 'companyAppointments']);
		Route::get('unplanneds', [AppointmentController::class, 'unplannedAppointments']);
		Route::get('trasheds', [AppointmentController::class, 'trashedAppointments']);
		Route::get('of_customer', [AppointmentController::class, 'customerAppointments']);
		Route::post('store', [AppointmentController::class, 'store']);
		Route::get('view', [AppointmentController::class, 'view']);
		Route::match(['PUT', 'PATCH'], 'update', [AppointmentController::class, 'update']);
		Route::delete('delete', [AppointmentController::class, 'delete']);
		Route::patch('restore', [AppointmentController::class, 'restore']);

		Route::post('cancel', [AppointmentController::class, 'cancel']);
		Route::post('reschedule', [AppointmentController::class, 'reschedule']);
		Route::post('execute', [AppointmentController::class, 'execute']);
		Route::post('process', [AppointmentController::class, 'process']);
		Route::post('calculate', [AppointmentController::class, 'calculate']);

		Route::post('generate_invoice', [AppointmentController::class, 'generateInvoice']);

		/**
		 * Sub Appointments Module
		 */
		Route::group(['prefix' => 'subs'], function () {
			Route::get('/', [SubAppointmentController::class, 'subAppointments']);
			Route::post('store', [SubAppointmentController::class, 'store']);
			Route::match(['PUT', 'PATCH'], 'update', [SubAppointmentController::class, 'update']);
			Route::post('cancel', [SubAppointmentController::class, 'cancel']);
			Route::post('reschedule', [SubAppointmentController::class, 'reschedule']);
			Route::post('execute', [SubAppointmentController::class, 'execute']);
			Route::post('process', [SubAppointmentController::class, 'process']);
			Route::delete('delete', [SubAppointmentController::class, 'delete']);

			/**
			 * Sub Appointment Work Module
			 */
			Route::group(['prefix' => 'works'], function () {
				Route::get('/', [SubAppointmentWorkController::class, 'subAppointmentWorks']);
				Route::post('store', [SubAppointmentWorkController::class, 'store']);
				Route::post('attach', [SubAppointmentWorkController::class, 'attach']);
				Route::post('attach_many', [SubAppointmentWorkController::class, 'attachMany']);
				Route::post('detach', [SubAppointmentWorkController::class, 'detach']);
				Route::post('detach_many', [SubAppointmentWorkController::class, 'detachMany']);
				Route::post('truncate', [SubAppointmentWorkController::class, 'truncate']);
			});
		});

		/**
		 * Appointment Employees Module
		 */
		Route::group(['prefix' => 'employees'], function () {
			Route::get('/', [AppointmentEmployeeController::class, 'appointmentEmployees']);
			Route::get('trasheds', [AppointmentEmployeeController::class, 'trashedAppointmentEmployees']);
			Route::post('assign', [AppointmentEmployeeController::class, 'assignEmployee']);
			Route::post('unassign', [AppointmentEmployeeController::class, 'unassignEmployee']);
		});

		/**
		 * Appointment Work Module
		 */
		Route::group(['prefix' => 'works'], function () {
			Route::get('/', [AppointmentWorkController::class, 'appointmentWorks']);
			Route::post('store_attach', [AppointmentWorkController::class, 'storeAttach']);
			Route::post('attach', [AppointmentWorkController::class, 'attach']);
			Route::post('attach_many', [AppointmentWorkController::class, 'attachMany']);
			Route::post('detach', [AppointmentWorkController::class, 'detach']);
			Route::post('detach_many', [AppointmentWorkController::class, 'detachMany']);
			Route::post('truncate', [AppointmentWorkController::class, 'truncate']);
		});

		/**
		 * Appointment Cost Module
		 */
		Route::group(['prefix' => 'costs'], function () {
			Route::get('/', [AppointmentCostController::class, 'appointmentCosts']);
			Route::post('store_record', [AppointmentCostController::class, 'storeRecord']);
			Route::post('record', [AppointmentCostController::class, 'record']);
			Route::post('record_many', [AppointmentCostController::class, 'recordMany']);
			Route::post('unrecord', [AppointmentCostController::class, 'unrecord']);
			Route::post('unrecord_many', [AppointmentCostController::class, 'unrecordMany']);
			Route::post('truncate', [AppointmentCostController::class, 'truncate']);
		});
	});

	/**
	 * Company Receipt Module
	 */
	Route::group(['prefix' => 'receipts'], function () {
		Route::get('/', [ReceiptController::class, 'receipts']);
		Route::get('trasheds', [ReceiptController::class, 'trashedReceipts']);
		Route::post('store', [ReceiptController::class, 'store']);
		Route::match(['PUT', 'PATCH'], 'update', [ReceiptController::class, 'update']);
		Route::delete('delete', [ReceiptController::class, 'delete']);
		Route::patch('restore', [ReceiptController::class, 'restore']);
	});

	/**
	 * Company Cost Module
	 */
	Route::group(['prefix' => 'costs'], function () {
		Route::get('/', [CostController::class, 'companyCosts']);
		Route::post('store', [CostController::class, 'store']);
		Route::get('view', [CostController::class, 'view']);
		Route::match(['PUT', 'PATCH'], 'update', [CostController::class, 'update']);
		Route::delete('delete', [CostController::class, 'delete']);
		Route::patch('restore', [CostController::class, 'restore']);

		Route::group(['prefix' => 'receipt'], function () {
			Route::post('attach', [CostReceiptController::class, 'attach']);
			Route::post('replace', [CostReceiptController::class, 'replace']);
		});
	});

	/**
	 * Company Revenue Module
	 */
	Route::group(['prefix' => 'revenues'], function () {
		Route::get('/', [RevenueController::class, 'companyRevenues']);
		Route::post('store', [RevenueController::class, 'store']);
		Route::match(['PUT', 'PATCH'], 'update', [RevenueController::class, 'update']);
		Route::delete('delete', [RevenueController::class, 'delete']);
		Route::patch('restore', [RevenueController::class, 'restore']);

		Route::group(['prefix' => 'receipt'], function () {
			Route::post('attach', [RevenueReceiptController::class, 'attach']);
			Route::post('replace', [RevenueReceiptController::class, 'replace']);
		});
	});

	/**
	 * Company Car Module
	 */
	Route::group(['prefix' => 'cars'], function () {
		Route::get('/', [CarController::class, 'companyCars']);
		Route::get('frees', [CarController::class, 'freeCars']);
		Route::get('trasheds', [CarController::class, 'trashedCars']);
		Route::post('store', [CarController::class, 'store']);
		Route::get('view', [CarController::class, 'view']);
		Route::post('set_image', [CarController::class, 'setCarImage']);
		Route::match(['PUT', 'PATCH'], 'update', [CarController::class, 'update']);
		Route::delete('delete', [CarController::class, 'delete']);
		Route::patch('restore', [CarController::class, 'restore']);

		/**
		 * Company Car Register Time Module
		 */
		Route::group(['prefix' => 'register_times'], function () {
			Route::get('/', [CarRegisterTimeController::class, 'carRegisterTimes']);
			Route::post('register', [CarRegisterTimeController::class, 'registerTime']);
			Route::post('register_to_worklist', [CarRegisterTimeController::class, 'registerToWorklist']);
			Route::post('mark_out', [CarRegisterTimeController::class, 'markOut']);
			Route::post('mark_return', [CarRegisterTimeController::class, 'markReturn']);
			Route::match(['PUT', 'PATCH'], 'update', [CarRegisterTimeController::class, 'update']);
			Route::delete('unregister', [CarRegisterTimeController::class, 'unregister']);

			/**
			 * Company Car Register Time Employees
			 */
			Route::group(['prefix' => 'assigned_employees'], function () {
				Route::get('/', [CarRegisterTimeEmployeeController::class, 'assignedEmployees']);
				Route::post('assign', [CarRegisterTimeEmployeeController::class, 'assignEmployee']);
				Route::get('show', [CarRegisterTimeEmployeeController::class, 'show']);
				Route::post('set_as_driver', [CarRegisterTimeEmployeeController::class, 'setAsDriver']);
				Route::post('set_out', [CarRegisterTimeEmployeeController::class, 'setOut']);
				Route::delete('unassign', [CarRegisterTimeEmployeeController::class, 'unassignEmployee']);
			});
		});
	});

	/**
	 * Company Invoice Module
	 */
	Route::group(['prefix' => 'invoices'], function () {
		Route::get('/', [InvoiceController::class, 'companyInvoices']);
		Route::get('overdue', [InvoiceController::class, 'companyOverdueInvoices']);
		Route::post('store', [InvoiceController::class, 'store']);
		Route::get('view', [InvoiceController::class, 'view']);
		Route::match(['PUT', 'PATCH'], 'update', [InvoiceController::class, 'update']);
		Route::post('send', [InvoiceController::class, 'send']);
		Route::post('print', [InvoiceController::class, 'print']);
		Route::post('print_draft', [InvoiceController::class, 'printDraft']);
		Route::post('send_reminder', [InvoiceController::class, 'sendReminder']);
		Route::post('send_first_reminder', [InvoiceController::class, 'sendFirstReminder']);
		Route::post('send_second_reminder', [InvoiceController::class, 'sendSecondReminder']);
		Route::post('send_third_reminder', [InvoiceController::class, 'sendThirdReminder']);
		Route::post('forward_to_debt_collector', [InvoiceController::class, 'forwardToDebtCollector']);
		Route::match(['PUT', 'PATCH'], 'change_status', [InvoiceController::class, 'changeStatus']);
		Route::post('mark_as_paid', [InvoiceController::class, 'markAsPaid']);
		Route::delete('delete', [InvoiceController::class, 'delete']);

		/**
		 * Invoice Item Module
		 */
		Route::group(['prefix' => 'items'], function () {
			Route::get('/', [InvoiceItemController::class, 'invoiceItems']);
			Route::post('store', [InvoiceItemController::class, 'store']);
			Route::match(['PUT', 'PATCH'], 'update', [InvoiceItemController::class, 'update']);
			Route::delete('delete', [InvoiceItemController::class, 'delete']);
		});

		/**
		 * Invoice Payment Term Module
		 */
		Route::group(['prefix' => 'payment_terms'], function () {
			Route::get('/', [PaymentTermController::class, 'paymentTerms']);
			Route::post('store', [PaymentTermController::class, 'store']);
			Route::match(['PUT', 'PATCH'], 'update', [PaymentTermController::class, 'update']);
			Route::post('mark_as_paid', [PaymentTermController::class, 'markAsPaid']);
			Route::post('cancel_paid_status', [PaymentTermController::class, 'cancelPaidStatus']);
			Route::post('forward_to_debt_collector', [PaymentTermController::class, 'forwardToDebtCollector']);
			Route::delete('delete', [PaymentTermController::class, 'delete']);
		});
	});

	/**
	 * Company Quotation Module
	 */
	Route::group(['prefix' => 'quotations'], function () {
		Route::get('/', [QuotationController::class, 'companyQuotations']);
		Route::get('trasheds', [QuotationController::class, 'trashedQuotations']);
		Route::get('of_customer', [QuotationController::class, 'customerQuotations']);
		Route::post('store', [QuotationController::class, 'store']);
		Route::get('view', [QuotationController::class, 'view']);
		Route::match(['PUT', 'PATCH'], 'update', [QuotationController::class, 'update']);
		Route::delete('delete', [QuotationController::class, 'delete']);

		/**
		 * Company Quotation Attachment Module
		 */
		Route::group(['prefix' => 'attachments'], function () {
			Route::get('/', [QuotationController::class, 'attachments']);
			Route::post('add', [QuotationController::class, 'addAttachment']);
			Route::delete('remove', [QuotationController::class, 'removeAttachment']);
		});

		/**
		 * Company Quotation Module Actions
		 */
		Route::post('send', [QuotationController::class, 'send']);
		Route::post('print', [QuotationController::class, 'print']);
		Route::post('revise', [QuotationController::class, 'revise']);
		Route::post('cancel', [QuotationController::class, 'cancel']);
		Route::post('honor', [QuotationController::class, 'honor']);
		Route::post('generate_invoice', [QuotationController::class, 'generateInvoice']);

		/**
		 * Company Quotation Work Module
		 */
		Route::group(['prefix' => 'works'], function () {
			Route::get('/', [QuotationWorkController::class, 'quotationWorks']);
			Route::post('store_attach', [QuotationWorkController::class, 'storeAttach']);
			Route::post('attach', [QuotationWorkController::class, 'attach']);
			Route::post('attach_many', [QuotationWorkController::class, 'attachMany']);
			Route::post('detach', [QuotationWorkController::class, 'detach']);
			Route::post('detach_many', [QuotationWorkController::class, 'detachMany']);
			Route::post('truncate', [QuotationWorkController::class, 'truncate']);
		});
	});

	/**
	 * Company Warranties
	 */
	Route::group(['prefix' => 'warranties'], function () {
		Route::get('/', [WarrantyController::class, 'companyWarranties']);
	});

	/**
	 * Company Payment Pickups
	 */
	Route::group(['prefix' => 'payment_pickups'], function () {
		Route::get('/', [PaymentPickupController::class, 'companyPaymentPickups']);
		Route::get('appointment', [PaymentPickupController::class, 'appointmentPaymentPickups']);
		Route::post('store', [PaymentPickupController::class, 'store']);
		Route::post('process', [PaymentPickupController::class, 'process']);
		Route::match(['PUT', 'PATCH'], 'update', [PaymentPickupController::class, 'update']);
		Route::delete('delete', [PaymentPickupController::class, 'delete']);
		Route::patch('restore', [PaymentPickupController::class, 'restore']);

		/**
		 * Payment pickup-pickupables
		 */
		Route::group(['prefix' => 'pickupables'], function () {
			Route::post('add', [PaymentPickupController::class, 'addPickupable']);
			Route::post('add_multiple', [PaymentPickupController::class, 'addMultiplePickupables']);
			Route::delete('remove', [PaymentPickupController::class, 'removePickupable']);
			Route::delete('remove_multiple', [PaymentPickupController::class, 'removeMultiplePickupables']);
			Route::delete('truncate', [PaymentPickupController::class, 'truncatePickupables']);
		});
	});

	/**
	 * Company Work Contracts
	 */
	Route::group(['prefix' => 'work_contracts'], function () {
		Route::get('/', [WorkContractController::class, 'companyWorkContracts']);
		Route::post('store', [WorkContractController::class, 'store']);
		Route::match(['PUT', 'PATCH'], 'update', [WorkContractController::class, 'update']);
		Route::delete('delete', [WorkContractController::class, 'delete']);

		/**
		 * Work contract works
		 */
		Route::get('works', [WorkController::class, 'contractWorks']);
	});

	/**
	 * Company work module
	 */
	Route::group(['prefix' => 'works'], function () {
		Route::get('/', [WorkController::class, 'companyWorks']);
		Route::get('appointment_finisheds', [WorkController::class, 'appointmentFinishedWorks']);
		Route::get('finisheds', [WorkController::class, 'finishedWorks']);
		Route::get('unfinisheds', [WorkController::class, 'unfinishedWorks']);
		Route::get('trasheds', [WorkController::class, 'trashedWorks']);
		Route::post('store', [WorkController::class, 'store']);
		Route::get('view', [WorkController::class, 'view']);
		Route::match(['PUT', 'PATCH'], 'update', [WorkController::class, 'update']);
		Route::delete('delete', [WorkController::class, 'delete']);
		Route::patch('restore', [WorkController::class, 'restore']);

		/**
		 * Execute work module
		 */
		Route::group(['prefix' => 'executes'], function () {
			Route::get('/', [ExecuteWorkController::class, 'executeWorks']);
			Route::get('trasheds', [ExecuteWorkController::class, 'trashedExecuteWorks']);
			Route::post('execute', [ExecuteWorkController::class, 'execute']);
			Route::post('mark_finished', [ExecuteWorkController::class, 'markFinished']);
			Route::post('mark_unfinished', [ExecuteWorkController::class, 'markUnfinished']);
			Route::delete('delete', [ExecuteWorkController::class, 'delete']);
			Route::patch('restore', [ExecuteWorkController::class, 'restore']);

			/**
			 * Execute work photo module
			 */
			Route::group(['prefix' => 'photos'], function () {
				Route::get('/', [ExecuteWorkPhotoController::class, 'executeWorkPhotos']);
				Route::get('trasheds', [ExecuteWorkPhotoController::class, 'trashedExecuteWorkPhotos']);
				Route::post('upload', [ExecuteWorkPhotoController::class, 'upload']);
				Route::post('upload_many', [ExecuteWorkPhotoController::class, 'uploadMany']);
				Route::delete('delete', [ExecuteWorkPhotoController::class, 'delete']);
			});
		});
		Route::post('process', [WorkController::class, 'process']);
		Route::post('mark_finish', [WorkController::class, 'markFinish']);
		Route::post('mark_unfinish', [WorkController::class, 'markUnfinsih']);
	});

	/**
	 * Address module
	 */
	Route::group(['prefix' => 'addresses'], function () {
		Route::get('/', [AddressController::class, 'companyAddresses']);
		Route::get('trasheds', [AddressController::class, 'companyTrashedAddresses']);
		Route::post('store', [AddressController::class, 'store']);
		Route::get('view', [AddressController::class, 'view']);
		Route::match(['PUT', 'PATCH'], 'update', [AddressController::class, 'update']);
		Route::delete('delete', [AddressController::class, 'delete']);
		Route::patch('restore', [AddressController::class, 'restore']);

		/**
		 * Customer address module
		 */
		Route::group(['prefix' => 'customer'], function () {
			Route::get('/', [CustomerAddressController::class, 'customerAddresses']);
			Route::get('trasheds', [CustomerAddressController::class, 'customerTrashedAddresses']);
			Route::post('store', [CustomerAddressController::class, 'store']);
			Route::get('view', [CustomerAddressController::class, 'view']);
			Route::match(['PUT', 'PATCH'], 'update', [CustomerAddressController::class, 'update']);
			Route::delete('delete', [CustomerAddressController::class, 'delete']);
			Route::patch('restore', [CustomerAddressController::class, 'restore']);
		});
		
		/**
		 * Owner address module
		 */
		Route::group(['prefix' => 'owner'], function () {
			Route::get('/', [OwnerAddressController::class, 'ownerAddresses']);
			Route::get('trasheds', [OwnerAddressController::class, 'ownerTrashedAddresses']);
			Route::post('store', [OwnerAddressController::class, 'store']);
			Route::get('view', [OwnerAddressController::class, 'view']);
			Route::match(['PUT', 'PATCH'], 'update', [OwnerAddressController::class, 'update']);
			Route::delete('delete', [OwnerAddressController::class, 'delete']);
			Route::patch('restore', [OwnerAddressController::class, 'restore']);
		});

		/**
		 * Employee address module
		 */
		Route::group(['prefix' => 'employee'], function () {
			Route::get('/', [EmployeeAddressController::class, 'employeeAddresses']);
			Route::get('trasheds', [EmployeeAddressController::class, 'employeeTrashedAddresses']);
			Route::post('store', [EmployeeAddressController::class, 'store']);
			Route::get('view', [EmployeeAddressController::class, 'view']);
			Route::match(['PUT', 'PATCH'], 'update', [EmployeeAddressController::class, 'update']);
			Route::delete('delete', [EmployeeAddressController::class, 'delete']);
			Route::patch('restore', [EmployeeAddressController::class, 'restore']);
		});
	});

	/**
	 * Company Post It Module
	 */
	Route::group(['prefix' => 'post_its'], function () {
		Route::get('/', [PostItController::class, 'companyPostIts']);
		Route::post('store', [PostItController::class, 'store']);
		Route::match(['PUT', 'PATCH'], 'update', [PostItController::class, 'update']);
		Route::post('assign_user', [PostItController::class, 'assignUser']);
		Route::post('unassign_user', [PostItController::class, 'unassignUser']);
		Route::delete('delete', [PostItController::class, 'delete']);
	});

	/**
	 * Company registration invitations module
	 */
	Route::group(['prefix' => 'register_invitations'], function () {
		Route::post('invite_employee', [RegisterInvitationController::class, 'inviteEmployee']);
		Route::post('invite_owner', [RegisterInvitationController::class, 'inviteOwner']);
	});

	/**
	 * Company Analytic Module
	 */
	Route::group(['prefix' => 'analytics'], function () {
		Route::get('revenue_trends', [AnalyticController::class, 'revenueTrends']);
	});
});