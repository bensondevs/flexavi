<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PaymentTerm;

class PaymentTermController extends Controller
{
    /**
     * Get all payment terms statuses enums
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function allStatuses()
    {
        $statuses = PaymentTerm::collectAllStatuses();
        return response()->json($statuses);
    }
}
