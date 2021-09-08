<?php

namespace App\Http\Controllers\Api\Company\Receipts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\ReceiptRepository;

class RevenueReceiptController extends Controller
{
    private $receipt;

    public function __construct(ReceiptRepository $receipt)
    {
        $this->receipt = $receipt;
    }

    public function attach(AttachRequest $request)
    {
        $receipt = $request->getReceipt();
        $this->receipt->setModel($receipt);

        $revenue = $request->getRevenue();
        $this->receipt->attachTo($revenue);

        return apiResponse($this->receipt);
    }

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