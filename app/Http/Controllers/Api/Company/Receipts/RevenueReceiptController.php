<?php

namespace App\Http\Controllers\Api\Company\Receipts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ReceiptRepository;

class RevenueReceiptController extends Controller
{
    /**
     * Receipt Repository Class Container
     * 
     * @var \App\Repositories\ReceiptRepository
     */
    private $receipt;

    /**
     * Controller constructor method
     * 
     * @param \App\Repositories\ReceiptRepository  $receipt
     * @return void
     */
    public function __construct(ReceiptRepository $receipt)
    {
        $this->receipt = $receipt;
    }

     /**
     * Attach receipt to a revenue
     * 
     * @param AttachRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function attach(AttachRequest $request)
    {
        $receipt = $request->getReceipt();
        $this->receipt->setModel($receipt);

        $revenue = $request->getRevenue();
        $this->receipt->attachTo($revenue);

        return apiResponse($this->receipt);
    }

    /**
     * Replace receipt of a revenue
     * 
     * @param ReplaceRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function replace(ReplaceRequest $request)
    {
        $input = $request->validated();
        $receipt = $this->receipt->save($input);
        $this->receipt->setModel($receipt);

        $revenue = $request->getRevenue();
        $this->receipt->replace($revenue);

        return apiResponse($this->receipt);
    }
}