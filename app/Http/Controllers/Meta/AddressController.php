<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Address;

class AddressController extends Controller
{
    public function allAddressTypes()
    {
        $types = Address::collectAllTypes();
        return response()->json($types);
    }
}
