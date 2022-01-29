<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use App\Enums\Setting\{
    SettingValueDataType as ValueDataType,
    SettingType as Type
};
use App\Http\Resources\SettingResource;
use App\Enums\SettingValue\SettingValueType as ValueType;
use App\Models\Setting;

class SettingController extends Controller
{
    /**
     * Get all setting types
     * 
     * This should be the values for the settings tab
     * 
     * @return \Illuminate\Support\Facades\Response
     */
    public function allTypes()
    {
        $types = Type::asSelectArray();
        return response()->json($types);
    }

    /**
     * Get all setting value data types
     * 
     * @return \Illuminate\Support\Facades\Response
     */
    public function allValueDataTypes()
    {
        return response()->json(ValueDataType::asSelectArray());
    }

    /**
     * Get all value type
     * 
     * @return \Illuminate\Support\Facades\Response
     */
    public function allValueTypes()
    {
        return response()->json(ValueType::asSelectArray());
    }
}