<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Invoices\SaveInvoiceRequest;
use App\Http\Requests\Invoices\PopulateInvoicesRequest;

class InvoiceController extends Controller
{
    private $invoice;

    public function __construct(InvoiceRepository $invoice)
    {
    	$this->invoice = $invoice;
    }

    public function companyInvoices(PopulateInvoicesRequest $request)
    {
        $invoices = $this->invoice->all($request->options());
        $invoices = $this->invoice->paginate();

        return response()->json(['invoices' => $invoices]);
    }

    public function store(SaveInvoiceRequest $request)
    {
        $invoice = $this->invoice->save($request->onlyInRules());

        return apiResponse($this->invoice, $invoice);
    }

    public function update(SaveInvoiceRequest $request)
    {
        $this->invoice->setModel($request->getInvoice());
        $invoice = $this->invoice->save($request->onlyInRules());

        return apiResponse($this->invoice, $invoice);
    }

    public function delete(Request $request)
    {
        $this->invoice->find($request->input('id'));
        $this->invoice->delete();

        return apiResponse($this->invoice);
    }
}
