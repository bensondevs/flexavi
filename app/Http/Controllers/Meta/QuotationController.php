<?php

namespace App\Http\Controllers\Meta;

use App\Enums\Quotation\QuotationCanceller;
use App\Enums\Quotation\QuotationDamageCause;
use App\Enums\Quotation\QuotationPaymentMethod;
use App\Enums\Quotation\QuotationStatus;
use App\Enums\Quotation\QuotationType;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

/**
 * @see \Tests\Feature\Meta\QuotationTest
 *      To the controller unit tester class.
 */
class QuotationController extends Controller
{
    /**
     * Get all quotation types enums
     *
     * @return JsonResponse
     */
    public function allTypes(): JsonResponse
    {
        return response()->json(
            QuotationType::asSelectArray()
        );
    }

    /**
     * Get all quotation statuses enums
     *
     * @return JsonResponse
     */
    public function allStatuses(): JsonResponse
    {
        return response()->json(
            QuotationStatus::asSelectArray()
        );
    }

    /**
     * Get all quotation payment methods statuses enums
     *
     * @return JsonResponse
     */
    public function allPaymentMethods(): JsonResponse
    {
        return response()->json(
            QuotationPaymentMethod::asSelectArray()
        );
    }

    /**
     * Get all quotation damage causes enums
     *
     * @return JsonResponse
     */
    public function allDamageCauses(): JsonResponse
    {
        return response()->json(
            QuotationDamageCause::asSelectArray()
        );
    }

    /**
     * Get all quotation cancellers enums
     *
     * @return JsonResponse
     */
    public function allCancellers(): JsonResponse
    {
        return response()->json(
            QuotationCanceller::asSelectArray()
        );
    }
}
