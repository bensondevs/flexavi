<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ExecuteWorkPhoto;

class ExecuteWorkPhotoController extends Controller
{
    /**
     * Get all photo condition types enums
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function allPhotoConditionTypes()
    {
        $types = ExecuteWorkPhoto::collectAllPhotoConditionTypes();
        return response()->json($types);
    }
}
