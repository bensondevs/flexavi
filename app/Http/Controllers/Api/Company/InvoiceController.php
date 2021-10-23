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
    private $invoice;

    public function __construct(InvoiceRepository $invoice)
    {
    	$this->invoice = $invoice;
    }

    public function companyInvoices(PopulateRequest $request)
    {
        $options = $request->options();

        $invoices = $this->invoice->all($options, true);
        $invoices = InvoiceResource::apiCollection($invoices);

        return response()->json(['invoices' => $invoices]);
    }

    public function companyOverdueInvoices(PopulateRequest $request)
    {
        $options = $request->options();

        $invoices = $this->invoice->overdueInvoices($options, true);
        $invoices = InvoiceResource::apiCollection($invoices);

        return response()->json(['invoices' => $invoices]);
    }

    public function store(SaveRequest $request)
    {
        $input = $request->validated();
        $invoice = $this->invoice->save($input);

        return apiResponse($this->invoice);
    }

    public function update(UpdateRequest $request)
    {
        $invoice = $request->getInvoice();
        $this->invoice->setModel($invoice);

        $input = $request->validated();
        $this->invoice->save($input);

        return apiResponse($this->invoice);
    }

    public function send(SendRequest $request)
    {
        $invoice = $request->getInvoice();
        $this->invoice->setModel($invoice);

        $destinationEmail = $request->input('destination_email');
        $this->invoice->send($destinationEmail);

        return apiResponse($this->invoice);
    }

    public function print(FindRequest $request)
    {
        $invoice = $request->getInvoice();
        $this->invoice->setModel($invoice);

        $this->invoice->print();

        $invoice = $this->invoice->getModel();
        $invoice = new InvoiceResource($invoice);
        return apiResponse($this->invoice, ['invoice' => $invoice]);
    }

    public function printDraft(FindRequest $request)
    {
        $invoice = $request->getInvoice();
        $this->invoice->setModel($invoice);

        $this->invoice->printDraft();

        $invoice = $this->invoice->getModel();
        $invoice = new InvoiceResource($invoice);
        return apiResponse($this->invoice, ['invoice' => $invoice]);
    }

    public function sendReminder(SendReminderRequest $request)
    {
        $invoice = $request->getInvoice();

        $this->invoice->setModel($invoice);
        $this->invoice->sendReminder();

        return apiResponse($this->invoice);
    }

    public function changeStatus(ChangeStatusRequest $request)
    {
        $invoice = $request->getInvoice();
        $this->invoice->setModel($invoice);

        $status = $request->input('status');
        $this->invoice->changeStatus($status);

        return apiResponse($this->invoice);
    }

    public function markAsPaid(FindRequest $request)
    {
        $invoice = $request->getInvoice();
        $this->invoice->setModel($invoice);

        $isViaDebtCollector = $request->input('is_via_debt_collector');
        $this->invoice->markAsPaid($isViaDebtCollector);

        return apiResponse($this->invoice);
    }

    public function delete(DeleteRequest $request)
    {
        $invoice = $request->getInvoice();
        $this->invoice->setModel($invoice);

        $force = $request->input('force');
        $this->invoice->delete($force);

        return apiResponse($this->invoice);
    }
}
