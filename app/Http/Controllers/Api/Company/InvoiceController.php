<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Invoices\PopulateCompanyInvoicesRequest as PopulateRequest;
use App\Http\Requests\Invoices\SaveInvoiceRequest as SaveRequest;
use App\Http\Requests\Invoices\FindInvoiceRequest as FindRequest;

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
        $invoices = $this->invoice->all($request->options());
        $invoices = $this->invoice->paginate();
        $invoices = InvoiceResource::apiCollection($invoices);

        return response()->json(['invoices' => $invoices]);
    }

    public function items(FindRequest $request)
    {
        $invoice = $request->getInvoice();
        return response()->json(['items' => $invoice->items]);
    }

    public function send(FindRequest $request)
    {
        $invoice = $request->getInvoice();

        $this->invoice->setModel($invoice);
        $this->invoice->send();

        return apiResponse($this->invoice);
    }

    public function sendFirstReminder(FindRequest $request)
    {
        $invoice = $request->getInvoice();

        $this->invoice->setModel($invoice);
        $this->invoice->sendFirstReminder();

        return apiResponse($this->invoice);
    }

    public function sendSecondReminder(FindRequest $request)
    {
        $invoice = $request->getInvoice();

        $this->invoice->setModel($invoice);
        $this->invoice->sendSecondReminder();

        return apiResponse($this->invoice);
    }

    public function sendThirdReminder(FindRequest $request)
    {
        $invoice = $request->getInvoice();

        $this->invoice->setModel($invoice);
        $this->invoice->sendThirdReminder();

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

    public function delete(Request $request)
    {
        $this->invoice->find($request->input('id'));
        $this->invoice->delete();

        return apiResponse($this->invoice);
    }
}
