<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Company\CarController;
use App\Http\Controllers\Api\Company\InvoiceController;
	use App\Http\Controllers\Api\Company\InvoiceItemController;
use App\Http\Controllers\Api\Company\CompanyController;
use App\Http\Controllers\Api\Company\AddressController;
use App\Http\Controllers\Api\Company\EmployeeController;
use App\Http\Controllers\Api\Company\CustomerController;
use App\Http\Controllers\Api\Company\QuotationController;
use App\Http\Controllers\Api\Company\OwnerController;
use App\Http\Controllers\Api\Company\InspectorController;
use App\Http\Controllers\Api\Company\PaymentTermController;
use App\Http\Controllers\Api\Company\WorkdayController;
	use App\Http\Controllers\Api\Company\WorklistController;
		use App\Http\Controllers\Api\Company\WorklistAppointmentController;
		use App\Http\Controllers\Api\Company\AppointmentController;
			use App\Http\Controllers\Api\Company\SubAppointmentController;
			use App\Http\Controllers\Api\Company\Costs\AppointmentCostController;
			use App\Http\Controllers\Api\Company\AppointmentWorkerController;
use App\Http\Controllers\Api\Company\CostController;
use App\Http\Controllers\Api\Company\RegisterInvitationController;
use App\Http\Controllers\Api\Company\WorkController;
use App\Http\Controllers\Api\Company\WorkContractController;
use App\Http\Controllers\Api\Company\ExecuteWorkController;
	use App\Http\Controllers\Api\Company\ExecuteWorkPhotoController;

Route::post('register', [CompanyController::class, 'register']);

Route::group(['middleware' => ['has_company']], function () {
	Route::get('user', [CompanyController::class, 'userCompany']);
	Route::post('upload_logo', [CompanyController::class, 'uploadCompanyLogo']);
	Route::match(['PUT', 'PATCH'], 'update', [CompanyController::class, 'update']);

	/*
		Company Owners Module
	*/
	Route::group(['prefix' => 'owners'], function () {
		Route::get('/', [OwnerController::class, 'companyOwners']);
		Route::get('inviteables', [OwnerController::class, 'inviteableOwners']);
		Route::get('trasheds', [OwnerController::class, 'trashedOwners']);
		Route::post('store', [OwnerController::class, 'store']);
		Route::match(['PUT', 'PATCH'], 'update', [OwnerController::class, 'update']);
		Route::delete('delete', [OwnerController::class, 'delete']);
		Route::patch('restore', [OwnerController::class, 'restore']);
	});

	/*
		Company Employee Module
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

	/*
		Company Customer Module
	*/
	Route::group(['prefix' => 'customers'], function () {
		Route::get('/', [CustomerController::class, 'companyCustomers']);
		Route::get('trasheds', [CustomerController::class, 'trashedCustomers']);
		Route::post('store', [CustomerController::class, 'store']);
		Route::match(['PUT', 'PATCH'], 'update', [CustomerController::class, 'update']);
		Route::delete('delete', [CustomerController::class, 'delete']);
		Route::patch('restore', [CustomerController::class, 'restore']);

		Route::get('appointments', [AppointmentController::class, 'customerAppointments']);
		Route::get('quotations', [QuotationController::class, 'customerQuotations']);
	});

	/*
		Company Workday Module
	*/
	Route::group(['prefix' => 'workdays'], function () {
		Route::get('/', [WorkdayController::class, 'companyWorkdays']);
		Route::get('current', [WorkdayController::class, 'currentWorkday']);
		Route::post('process', [WorkdayController::class, 'process']);
		Route::post('calculate', [WorkdayController::class, 'calculate']);
	});

	/*
		Company Worlist Module
	*/
	Route::group(['prefix' => 'worklists'], function () {
		Route::get('/', [WorklistController::class, 'companyWorklists']);
		Route::get('/of_workday', [WorklistController::class, 'workdayWorklists']);
		Route::get('trasheds', [WorklistController::class, 'trashedWorklists']);
		Route::post('store', [WorklistController::class, 'store']);
		Route::post('process', [WorklistController::class, 'process']);
		Route::post('calculate', [WorklistController::class, 'calculate']);
		Route::match(['PUT', 'PATCH'], 'update', [WorklistController::class, 'update']);
		Route::delete('delete', [WorklistController::class, 'delete']);
		Route::patch('restore', [WorklistController::class, 'restore']);

		Route::group(['prefix' => 'appointments'], function () {
			Route::get('/', [WorklistAppointmentController::class, 'worklistAppointments']);
		});
	});

	/*
		Company Appointment Module
	*/
	Route::group(['prefix' => 'appointments'], function () {
		Route::get('/', [AppointmentController::class, 'companyAppointments']);
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

		Route::post('generate_invoice', [AppointmentController::class, 'generateInvoice']);

		/*
			Sub Appointments Module
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
		});

		/*
			Appointment Workers Module
		*/
		Route::group(['prefix' => 'workers'], function () {
			Route::get('/', [AppointmentWorkerController::class, 'companyAppointmentWorkers']);
			Route::post('store', [AppointmentWorkerController::class, 'store']);
			Route::match(['PUT', 'PATCH'], 'update', [AppointmentWorkerController::class, 'update']);
			Route::delete('delete', [AppointmentWorkerController::class, 'delete']);
		});

		/*
			Appointment Cost Module
		*/
		Route::group(['prefix' => 'costs'], function () {
			Route::get('/', [AppointmentCostController::class, 'appointmentCosts']);
			Route::post('store', [AppointmentCostController::class, 'store']);
			Route::post('record', [AppointmentCostController::class, 'record']);
			Route::post('record_many', [AppointmentCostController::class, 'recordMany']);
			Route::post('unrecord', [AppointmentCostController::class, 'unrecord']);
			Route::post('unrecord_many', [AppointmentCostController::class, 'unrecordMany']);
			Route::post('truncate', [AppointmentCostController::class, 'truncate']);
		});
	});

	/*
		Company Cost Module
	*/
	Route::group(['prefix' => 'costs'], function () {
		Route::get('of_appointment', [CostController::class, 'appointmentCosts']);
	});

	/*
		Company Car Module
	*/
	Route::group(['prefix' => 'cars'], function () {
		Route::get('/', [CarController::class, 'companyCars']);
		Route::get('frees', [CarController::class, 'freeCars']);
		Route::get('trasheds', [CarController::class, 'trashedCars']);
		Route::post('store', [CarController::class, 'store']);
		Route::post('set_image', [CarController::class, 'setCarImage']);
		Route::match(['PUT', 'PATCH'], 'update', [CarController::class, 'update']);
		Route::delete('delete', [CarController::class, 'delete']);
		Route::patch('restore', [CarController::class, 'restore']);
	});

	/*
		Company Inspector Module
	*/
	/*Route::group(['prefix' => 'inspectors'], function () {
		Route::get('/', [InspectorController::class, 'companyInspectors']);
		Route::post('add', [InspectorController::class, 'add']);
		Route::delete('remove', [InspectorController::class, 'remove']);
	});*/

	/*
		Company Invoice Module
	*/
	Route::group(['prefix' => 'invoices'], function () {
		Route::get('/', [InvoiceController::class, 'companyInvoices']);
		Route::get('overdue', [InvoiceController::class, 'companyOverdueInvoices']);
		Route::match(['PUT', 'PATCH'], 'update', [InvoiceController::class, 'update']);
		Route::post('send', [InvoiceController::class, 'send']);
		Route::post('send_reminder', [InvoiceController::class, 'sendReminder']);
		Route::match(['PUT', 'PATCH'], 'changeStatus', [InvoiceController::class, 'changeStatus']);
		Route::post('mark_as_paid', [InvoiceController::class, 'markAsPaid']);
		Route::delete('delete', [InvoiceController::class, 'delete']);

		/*
			Invoice Item Module
		*/
		Route::group(['prefix' => 'items'], function () {
			Route::get('/', [InvoiceItemController::class, 'invoiceItems']);
			Route::post('store', [InvoiceItemController::class, 'store']);
			Route::match(['PUT', 'PATCH'], 'update', [InvoiceItemController::class, 'update']);
			Route::delete('delete', [InvoiceItemController::class, 'delete']);
		});

		/*
			Invoice Payment Term Module
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

	/*
		Company Quotation Module
	*/
	Route::group(['prefix' => 'quotations'], function () {
		Route::get('/', [QuotationController::class, 'companyQuotations']);
		Route::get('of_customer', [QuotationController::class, 'customerQuotations']);
		Route::post('store', [QuotationController::class, 'store']);
		Route::match(['PUT', 'PATCH'], 'update', [QuotationController::class, 'update']);
		Route::delete('delete', [QuotationController::class, 'delete']);

		/*
			Attachments
		*/
		Route::group(['prefix' => 'attachments'], function () {
			Route::get('/', [QuotationController::class, 'attachments']);
			Route::post('add', [QuotationController::class, 'addAttachment']);
			Route::delete('remove', [QuotationController::class, 'removeAttachment']);
		});

		/*
			Actions
		*/
		Route::post('send', [QuotationController::class, 'send']);
		Route::post('print', [QuotationController::class, 'print']);
		Route::post('revise', [QuotationController::class, 'revise']);
		Route::post('cancel', [QuotationController::class, 'cancel']);
		Route::post('honor', [QuotationController::class, 'honor']);

		/*
			Quotation Works
		*/
		Route::get('works', [WorkController::class, 'quotationWorks']);
	});

	/*
		Schedule Module
	*/
	Route::group(['prefix' => 'schedules'], function () {
		/*Route::get('/', [ScheduleController::class, 'companyWorks']);
		Route::post('store', [ScheduleController::class, 'store']);
		Route::match(['PUT', 'PATCH'], 'update', [ScheduleController::class, 'update']);
		Route::delete('delete', [ScheduleController::class, 'delete']);*/

		/*
			Schedule Car Module
		*/
		Route::group(['prefix' => 'cars'], function () {
			/*Route::get('/', [ScheduleCarController::class, 'companyScheduleCars']);
			Route::post('store', [ScheduleCarController::class, 'store']);
			Route::match(['PUT', 'PATCH'], 'update', [ShceduleCarController::class, 'update']);
			Route::delete('delete', [ScheduleCarController::class, 'delete']);*/
		});

		/*
			Schedule Employee Module
		*/
		Route::group(['prefix' => 'employees'], function () {
			/*Route::get('/', [ScheduleEmployeeController::class, 'companyScheduleEmployees']);
			Route::post('store', [ScheduleEmployeeController::class, 'store']);
			Route::match(['PUT', 'PATCH'], 'update', [ScheduleEmployeeController::class, 'update']);
			Route::delete('delete', [ScheduleEmployeeController::class, 'delete']);*/
		});
	});

	/*
		Company Work Contracts
	*/
	Route::group(['prefix' => 'work_contracts'], function () {
		Route::get('/', [WorkContractController::class, 'companyWorkContracts']);
		Route::post('store', [WorkContractController::class, 'store']);
		Route::match(['PUT', 'PATCH'], 'update', [WorkContractController::class, 'update']);
		Route::delete('delete', [WorkContractController::class, 'delete']);

		/*
			Quotation Works
		*/
		Route::get('works', [WorkController::class, 'contractWorks']);
	});

	/*
		Company Work Module
	*/
	Route::group(['prefix' => 'works'], function () {
		Route::get('of_quotation', [WorkController::class, 'quotationWorks']);
		Route::get('of_appointment', [WorkController::class, 'appointmentWorks']);
		Route::get('finisheds', [WorkController::class, 'finishedWorks']);
		Route::get('unfinisheds', [WorkController::class, 'unfinishedWorks']);

		Route::post('store', [WorkController::class, 'store']);
		Route::match(['PUT', 'PATCH'], 'update', [WorkController::class, 'update']);
		Route::delete('delete', [WorkController::class, 'delete']);

		/*
			Execute Work Module
		*/
		Route::group(['prefix' => 'execute'], function () {
			Route::post('execute', [ExecuteWorkController::class, 'execute']);
			Route::delete('delete', [ExecuteWorkController::class, 'delete']);

			/*
				Execute Work Photo Module
			*/
			Route::group(['prefix' => 'photos'], function () {
				Route::get('/', [ExecuteWorkPhotoController::class, 'exeuteWorkPhotos']);
				Route::post('upload_before', [ExecuteWorkPhotoController::class, 'uploadBefore']);
				Route::post('upload_after', [ExecuteWorkPhotoController::class, 'uploadAfter']);
				Route::delete('delete', [ExecuteWorkPhotoController::class, 'delete']);
			});
		});
	});

	/*
		Address List
	*/
	Route::group(['prefix' => 'addresses', 'as' => 'addresses.'], function () {
		Route::get('/', [AddressController::class, 'userAddresses']);
		Route::get('employee', [AddressController::class, 'employeeAddresses']);
	});

	/*
		Company Register Invitation Module
	*/
	Route::group(['prefix' => 'register_invitations', 'as' => 'register_invitations.'], function () {
		Route::post('invite_employee', [RegisterInvitationController::class, 'inviteEmployee']);
		Route::post('invite_owner', [RegisterInvitationController::class, 'inviteOwner']);
	});
});