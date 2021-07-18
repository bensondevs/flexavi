<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PaymentTerm;

class PaymentTermController extends Controller
{
    public function allStatuses()
    {
        $statuses = PaymentTerm::collectAllStatuses();

        return response()->json(['statuses' => $statuses]);
    }
}
