<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Addresses\FindAddressPro6PPRequest;
use App\Models\Address\Address;
use App\Services\Pro6PP\Pro6PPService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Get all address types enum
     *
     * @return JsonResponse
     * @see \Tests\Feature\Meta\AddressTest::test_get_all_address_types()
     *      to the feature test
     */
    public function allAddressTypes(): JsonResponse
    {
        $types = Address::collectAllTypes();
        return response()->json($types);
    }

    /**
     * Guess address by zipcode and house number
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function autocomplete(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'zipcode' => 'required|string',
            'house_number' => 'required|numeric',
        ]);

        $address = app(Pro6PPService::class)->autocomplete($validated);

        return response()
            ->json(['address' => $address])
            ->setStatusCode(isset($address->errors) ? 422 : 200);
    }
}
