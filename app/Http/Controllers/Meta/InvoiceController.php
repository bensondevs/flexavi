<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function allStatuses()
    {
        $statuses = Invoice::collectAllStatuses();

        return response()->json(['statuses' => $statuses]);
    }

    public function selectableStatuses()
    {
        $selectableStatuses = Invoice::collectStatusOptions();

        return response()->json(['statuses' => $selectableStatuses]);
    }

    public function allPaymentMethods()
    {
        $methods = Invoice::collectAllPaymentMethods();

        return response()->json(['payment_methods' => $methods]);
    }
}
