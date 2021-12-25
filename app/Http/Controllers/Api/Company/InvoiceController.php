<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Invoices\{
    PopulateCompanyInvoicesRequest as PopulateRequest,
    SaveInvoiceRequest as SaveRequest,
    FindInvoiceRequest as FindRequest,
    SendInvoiceRequest as SendRequest,
    SendInvoiceReminderRequest as SendReminderRequest,
    ChangeInvoiceStatusRequest as ChangeStatusRequest,
    UpdateInvoiceRequest as UpdateRequest,
    DeleteInvoiceRequest as DeleteRequest
};
use App\Http\Resources\InvoiceResource;
use App\Repositories\InvoiceRepository;

class InvoiceController extends Controller
{
    /**
     * Invoice Repository Class Container
     * 
     * @var \App\Repositories\InvoiceRepository
     */
    private $invoice;

    /**
     * Controller constructor method
     * 
     * @param \App\Repositories\InvoiceRepository  $invoice
     * @return void
     */
    public function __construct(InvoiceRepository $invoice)
    {
    	$this->invoice = $invoice;
    }

    /**
     * Populate company invoices
     * 
     * @param PopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function companyInvoices(PopulateRequest $request)
    {
        $options = $request->options();

        $invoices = $this->invoice->all($options, true);
        $invoices = InvoiceResource::apiCollection($invoices);

        return response()->json(['invoices' => $invoices]);
    }

    /**
     * Populate company overdue invoices
     * 
     * @param PopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function companyOverdueInvoices(PopulateRequest $request)
    {
        $options = $request->options();

        $invoices = $this->invoice->overdueInvoices($options, true);
        $invoices = InvoiceResource::apiCollection($invoices);

        return response()->json(['invoices' => $invoices]);
    }

    /**
     * Store invoice 
     * 
     * @param SaveRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function store(SaveRequest $request)
    {
        $input = $request->validated();
        $invoice = $this->invoice->save($input);

        return apiResponse($this->invoice);
    }

    /**
     * View invoice
     * 
     * @param FindRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function view(FindRequest $request)
    {
        $invoice = $request->getInvoice();
        $invoice->load($request->relations());
        $invoice = new InvoiceResource($invoice);

        return response()->json(['invoice' => $invoice]);
    }

    /**
     * Update invoice
     * 
     * @param UpdateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function update(UpdateRequest $request)
    {
        $invoice = $request->getInvoice();
        $this->invoice->setModel($invoice);

        $input = $request->validated();
        $this->invoice->save($input);

        return apiResponse($this->invoice);
    }

    /**
     * Send Invoice to target email or customer email
     * 
     * @param SendRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function send(SendRequest $request)
    {
        $invoice = $request->getInvoice();
        $this->invoice->setModel($invoice);

        $destinationEmail = $request->input('destination_email');
        $this->invoice->send($destinationEmail);

        return apiResponse($this->invoice);
    }

    /**
     * Print invoice and set the status to sent
     * 
     * @param FindRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function print(FindRequest $request)
    {
        $invoice = $request->getInvoice();
        $this->invoice->setModel($invoice);

        $this->invoice->print();

        $invoice = $this->invoice->getModel();
        $invoice = new InvoiceResource($invoice);
        return apiResponse($this->invoice, ['invoice' => $invoice]);
    }

    /**
     * Print draft the invoice
     * 
     * @param FindRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function printDraft(FindRequest $request)
    {
        $invoice = $request->getInvoice();
        $this->invoice->setModel($invoice);

        $this->invoice->printDraft();

        $invoice = $this->invoice->getModel();
        $invoice = new InvoiceResource($invoice);
        return apiResponse($this->invoice, ['invoice' => $invoice]);
    }

    /**
     * Send reminder to customer about the invoice
     * 
     * @param SendReminderRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function sendReminder(SendReminderRequest $request)
    {
        $invoice = $request->getInvoice();

        $this->invoice->setModel($invoice);
        $this->invoice->sendReminder();

        return apiResponse($this->invoice);
    }

    /**
     * Change status of invoice to selected status
     * 
     * @param ChangeStatusRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function changeStatus(ChangeStatusRequest $request)
    {
        $invoice = $request->getInvoice();
        $this->invoice->setModel($invoice);

        $status = $request->input('status');
        $this->invoice->changeStatus($status);

        return apiResponse($this->invoice);
    }

    /**
     * Mark invoice as paid
     * 
     * @param FindRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function markAsPaid(FindRequest $request)
    {
        $invoice = $request->getInvoice();
        $this->invoice->setModel($invoice);

        $isViaDebtCollector = $request->input('is_via_debt_collector');
        $this->invoice->markAsPaid($isViaDebtCollector);

        return apiResponse($this->invoice);
    }

    /**
     * Delete invoice
     * 
     * @param DeleteRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function delete(DeleteRequest $request)
    {
        $invoice = $request->getInvoice();
        $this->invoice->setModel($invoice);

        $force = $request->input('force');
        $this->invoice->delete($force);

        return apiResponse($this->invoice);
    }
}
