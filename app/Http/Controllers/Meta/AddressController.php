<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Address;

class AddressController extends Controller
{
    /**
     * Get all address types enum
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function allAddressTypes()
    {
        $types = Address::collectAllTypes();
        return response()->json($types);
    }
}