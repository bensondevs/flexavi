<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\InvoiceItems\SaveInvoiceItemRequest as SaveRequest;
use App\Http\Requests\InvoiceItems\DeleteInvoiceItemRequest as DeleteRequest;
use App\Http\Requests\InvoiceItems\UpdateInvoiceItemRequest as UpdateRequest;
use App\Http\Requests\InvoiceItems\PopulateInvoiceItemsRequest as PopulateRequest;

use App\Http\Resources\InvoiceItemResource;

use App\Repositories\InvoiceItemRepository;

class InvoiceItemController extends Controller
{
    private $item;

    public function __construct(InvoiceItemRepository $item)
    {
        $this->item = $item;
    }

    public function invoiceItems(PopulateRequest $request)
    {
        $options = $request->options();

        $items = $this->item->all($options, true);
        $items = InvoiceItemResource::apiCollection($items);

        return response()->json(['invoice_items' => $items]);
    }

    public function store(SaveRequest $request)
    {
        $input = $request->validated();
        $item = $this->item->save($input);

        return apiResponse($this->item);
    }

    public function update(UpdateRequest $request)
    {
        $item = $request->getInvoiceItem();
        $item = $this->item->setModel($item);

        $input = $request->validated();
        $item = $this->item->save($input);

        return apiResponse($this->item);
    }

    public function delete(DeleteRequest $request)
    {
        $item = $request->getInvoiceItem();

        $this->item->setModel($item);
        $this->item->delete();

        return apiResponse($this->item);
    }
}
