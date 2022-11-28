<?php

namespace App\Http\Controllers\Meta;

use App\Enums\Setting\SettingModule;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    /**
    * Get all setting's modules
    *
    * @return Illuminate\Support\Facades\Response
    */
    public function allModules()
    {
        $modules = SettingModule::asSelectArray();
        return response()->json($modules);
    }
}
