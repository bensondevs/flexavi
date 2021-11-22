<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\InvoiceItems\{
    SaveInvoiceItemRequest as SaveRequest,
    DeleteInvoiceItemRequest as DeleteRequest,
    UpdateInvoiceItemRequest as UpdateRequest,
    PopulateInvoiceItemsRequest as PopulateRequest
};

use App\Http\Resources\InvoiceItemResource;

use App\Repositories\InvoiceItemRepository;

class InvoiceItemController extends Controller
{
    /**
     * Invoice Item Repository Class Container
     * 
     * @var \App\Repositories\InvoiceItemRepository
     */
    private $item;

    /**
     * Controller constructor method
     * 
     * @param \App\Repositories\InvoiceItemRepository  $item
     * @return void
     */
    public function __construct(InvoiceItemRepository $item)
    {
        $this->item = $item;
    }

    /**
     * Populate invoice items
     * 
     * @param PopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function invoiceItems(PopulateRequest $request)
    {
        $options = $request->options();

        $items = $this->item->all($options, true);
        $items = InvoiceItemResource::apiCollection($items);

        return response()->json(['invoice_items' => $items]);
    }

    /**
     * Add item to invoice
     * 
     * @param SaveRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function store(SaveRequest $request)
    {
        $input = $request->validated();
        $item = $this->item->save($input);
        return apiResponse($this->item);
    }

    /**
     * Update invoice item
     * 
     * @param UpdateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function update(UpdateRequest $request)
    {
        $item = $request->getInvoiceItem();
        $item = $this->item->setModel($item);

        $input = $request->validated();
        $item = $this->item->save($input);

        return apiResponse($this->item);
    }

    /**
     * Delete invoice item
     * 
     * @param DeleteRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function delete(DeleteRequest $request)
    {
        $item = $request->getInvoiceItem();

        $this->item->setModel($item);
        $this->item->delete();

        return apiResponse($this->item);
    }
}
