<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use App\Enums\Setting\SettingType as Type;

class SettingController extends Controller
{
    /**
     * Get all setting types
     * 
     * @return \Illuminate\Support\Facades\Response
     */
    public function allTypes()
    {
        return response()->json(Type::asSelectArray());
    }
}
