<?php

namespace App\Http\Controllers\Api\Company\Receipts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\ReceiptRepository;

class CostReceiptController extends Controller
{
    private $receipt;

    public function __construct(ReceiptRepository $receipt)
    {
        $this->receipt = $receipt;
    }

    public function attach(AttachRequest $request)
    {
        $input = $request->validated();
        $receipt = $this->receipt->save($input);

        $cost = $request->getCost();
        $receipt = $this->receipt->attachTo($cost);

        return apiResponse($this->receipt);
    }

    public function replace(ReplaceRequest $request)
    {
        $cost = $request->getCost();
        $input = $request->validated();
        $receipt = $this->receipt->replace($cost, $input);
        
        return apiResponse($this->receipt);
    }
}
