<?php

namespace App\Http\Controllers\Meta;

use App\Enums\Notification\NotificationPopulateType;
use App\Enums\Notification\NotificationType;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
    * Get all notification types enums
    *
    * @return JsonResponse
    */
    public function allTypes(): JsonResponse
    {
        return response()->json(NotificationType::asSelectArray());
    }

    /**
    * Get all notification populate types enums
    *
    * @return JsonResponse
    */
    public function allPopulateTypes(): JsonResponse
    {
        return response()->json(NotificationPopulateType::asSelectArray());
    }
}
