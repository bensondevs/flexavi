<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Quotation;

class QuotationController extends Controller
{
    public function allTypes()
    {
        $types = Quotation::collectAllTypes();

        return response()->json(['types' => $types]);
    }

    public function allStatuses()
    {
        $statuses = Quotation::collectAllStatuses();

        return response()->json(['statuses' => $statuses]);
    }

    public function allPaymentMethods()
    {
        $methods = Quotation::collectAllPaymentMethods();

        return response()->json(['payment_methods' => $methods]);
    }

    public function allDamageCauses()
    {
        $causes = Quotation::collectAllDamageCauses();

        return response()->json(['damage_causes' => $causes]);
    }

    public function allCanceller()
    {
        $cancellers = Quotation::collectAllCanceller();

        return response()->json(['cancellers' => $cancellers]);
    }
}
