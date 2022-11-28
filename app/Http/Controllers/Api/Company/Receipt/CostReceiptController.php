<?php

namespace App\Http\Controllers\Api\Company\Receipt;

use App\Http\Controllers\Controller;
use App\Repositories\Receipt\ReceiptRepository;

class CostReceiptController extends Controller
{
    /**
     * Receipt Repository Class Container
     *
     * @var ReceiptRepository
     */
    private $receipt;

    /**
     * Controller constructor method
     *
     * @param ReceiptRepository $receipt
     * @return void
     */
    public function __construct(ReceiptRepository $receipt)
    {
        $this->receipt = $receipt;
    }

    /**
     * Attach receipt to a cost
     *
     * @param AttachRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function attach(AttachRequest $request)
    {
        $input = $request->validated();
        $receipt = $this->receipt->save($input);

        $cost = $request->getCost();
        $receipt = $this->receipt->attachTo($cost);

        return apiResponse($this->receipt);
    }

    /**
     * Replace receipt of a cost
     *
     * @param ReplaceRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function replace(ReplaceRequest $request)
    {
        $cost = $request->getCost();
        $input = $request->validated();
        $receipt = $this->receipt->replace($cost, $input);

        return apiResponse($this->receipt);
    }
}
