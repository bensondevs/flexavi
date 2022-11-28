<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use App\Models\ExecuteWork\ExecuteWorkPhoto;
use Illuminate\Http\JsonResponse;

class ExecuteWorkPhotoController extends Controller
{
    /**
     * Get all photo condition types enums
     *
     * @return JsonResponse
     */
    public function allPhotoConditionTypes(): JsonResponse
    {
        $types = ExecuteWorkPhoto::collectAllPhotoConditionTypes();
        return response()->json($types);
    }
}
