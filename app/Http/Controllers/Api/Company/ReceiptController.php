<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Receipts\PopulateCompanyReceiptsRequest as PopulateRequest;
use App\Http\Requests\Receipts\SaveReceiptRequest as SaveRequest;
use App\Http\Requests\Receipts\DeleteReceiptRequest as DeleteRequest;
use App\Http\Requests\Receipts\RestoreReceiptRequest as RestoreRequest;

use App\Http\Resources\ReceiptResource;

use App\Repositories\ReceiptRepository;

class ReceiptController extends Controller
{
    /**
     * Repository Container 
     * 
     * @var \App\Repositories\ReceiptRepository|null
     */
    private $receipt;

    /**
     * Create New Controller Instance
     * 
     * @param \App\Repositories\ReceiptRepository $receipt
     * @return void
     */
    public function __construct(ReceiptRepository $receipt)
    {
        $this->receipt = $receipt;
    }

    /**
     * Populate company receipts
     * 
     * @param PopulateRequest $request
     * @return json
     */
    public function receipts(PopulateRequest $request)
    {
        $options = $request->options();

        $receipts = $this->receipt->all($options, true);
        $receipts = ReceiptResource::apiCollection($receipts);

        return response()->json(['receipts' => $receipts]);
    }

    /**
     * Populate deleted company receipts
     * 
     * @param PopulateRequest $request
     * @return json
     */
    public function trashedReceipts(PopulateRequest $request)
    {
        $options = $request->options();

        $receipts = $this->receipt->trasheds($options, true);
        $receipts = ReceiptResource::apiCollection($receipts);

        return response()->json(['receipts' => $receipts]);
    }

    /**
     * Store Receipt
     * 
     * @param SaveRequest $request
     * @return json
     */
    public function store(SaveRequest $request)
    {
        $input = $request->validated();
        $receipt = $this->receipt->save($input);

        return apiResponse($this->receipt, ['receipt' => $receipt]);
    }

    /**
     * Update Receipt
     * 
     * @param SaveRequest $request
     * @return json
     */
    public function update(SaveRequest $request)
    {
        $receipt = $request->getReceipt();
        $this->receipt->setModel($receipt);

        $input = $request->validated();
        $this->receipt->save($input);

        return apiResponse($this->receipt);
    }

    /**
     * Delete Receipt
     * 
     * @param DeleteRequest $request
     * @return json
     */
    public function delete(DeleteRequest $request)
    {
        $receipt = $request->getReceipt();
        $this->receipt->setModel($receipt);

        $force = $request->input('force');
        $this->receipt->delete($force);

        return apiResponse($this->receipt);
    }

    /**
     * Restore Receipt
     * 
     * @param RestoreRequest $request
     * @return json
     */
    public function restore(RestoreRequest $request)
    {
        $receipt = $request->getReceipt();

        $this->receipt->setModel($receipt);
        $this->receipt->restore();

        return apiResponse($this->receipt);
    }
}
