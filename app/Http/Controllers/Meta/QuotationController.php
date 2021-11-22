<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Quotation;

class QuotationController extends Controller
{
    /**
     * Get all quotation types enums
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function allTypes()
    {
        $types = Quotation::collectAllTypes();
        return response()->json($types);
    }

    /**
     * Get all quotation statuses enums
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function allStatuses()
    {
        $statuses = Quotation::collectAllStatuses();
        return response()->json($statuses);
    }

    /**
     * Get all quotation payment methods statuses enums
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function allPaymentMethods()
    {
        $methods = Quotation::collectAllPaymentMethods();
        return response()->json($methods);
    }

    /**
     * Get all quotation damage causes enums
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function allDamageCauses()
    {
        $causes = Quotation::collectAllDamageCauses();
        return response()->json($causes);
    }

    /**
     * Get all quotation cancellers enums
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function allCancellers()
    {
        $cancellers = Quotation::collectAllCanceller();
        return response()->json($cancellers);
    }
}
