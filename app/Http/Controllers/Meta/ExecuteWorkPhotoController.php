<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ExecuteWorkPhoto;

class ExecuteWorkPhotoController extends Controller
{
    public function allPhotoConditionTypes()
    {
        $types = ExecuteWorkPhoto::collectAllPhotoConditionTypes();

        return response()->json(['photo_condition_types' => $types]);
    }
}
