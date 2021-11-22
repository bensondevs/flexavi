<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Invoice;

class InvoiceController extends Controller
{
    /**
     * Get all invoice statuses enums
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function allStatuses()
    {
        $statuses = Invoice::collectAllStatuses();
        return response()->json($statuses);
    }

    /**
     * Get selectable statuses enums
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function selectableStatuses()
    {
        $selectableStatuses = Invoice::collectStatusOptions();
        return response()->json($selectableStatuses);
    }

    /**
     * Get all invoice payment methods enums
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function allPaymentMethods()
    {
        $methods = Invoice::collectAllPaymentMethods();
        return response()->json($methods);
    }
}
