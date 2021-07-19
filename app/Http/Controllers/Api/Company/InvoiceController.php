<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Invoices\PopulateCompanyInvoicesRequest as PopulateRequest;
use App\Http\Requests\Invoices\SaveInvoiceRequest as SaveRequest;
use App\Http\Requests\Invoices\FindInvoiceRequest as FindRequest;
use App\Http\Requests\Invoices\UpdateInvoiceRequest as UpdateRequest;

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

    public function update(UpdateRequest $request)
    {
        $invoice = $request->getInvoice();
        $this->invoice->setModel($invoice);

        $input = $request->validated();
        $this->invoice->save($input);

        return apiResponse($this->invoice);
    }

    public function send(FindRequest $request)
    {
        $invoice = $request->getInvoice();

        $this->invoice->setModel($invoice);
        $this->invoice->send();

        return apiResponse($this->invoice);
    }

    public function print(FindRequest $request)
    {
        $invoice = $request->getInvoice();

        $this->invoice->setModel($invoice);
        $this->invoice->print();

        return apiResponse($this->invoice);
    }

    public function printDraft(FindRequest $request)
    {
        $invoice = $request->getInvoice();

        $this->invoice->setModel($invoice);
        $this->invoice->printDraft();

        return apiResponse($this->invoice);
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
        $this->invoice->delete();

        return apiResponse($this->invoice);
    }
}
