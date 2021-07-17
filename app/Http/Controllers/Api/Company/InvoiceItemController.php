<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\InvoiceItem\SaveInvoiceItemRequest as SaveRequest;
use App\Http\Requests\InvoiceItem\DeleteInvoiceItemRequest as DeleteRequest;

use App\Repositories\InvoiceItemRepository;

class InvoiceItemController extends Controller
{
    private $item;

    public function __construct(InvoiceItemRepository $item)
    {
        $this->item = $item;
    }

    public function add(SaveRequest $request)
    {
        $input = $request->validated();
        $item = $this->item->save($input);

        return apiResponse($this->item);
    }

    public function update(SaveRequest $request)
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
