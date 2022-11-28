<?php

namespace App\Http\Controllers\Meta;

use App\Enums\OwnerInvitation\OwnerInvitationStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class OwnerInvitationController extends Controller
{
    /**
    * Get all owner invitation statuses
    *
    * @return JsonResponse
    */
    public function allStatuses(): JsonResponse
    {
        return response()->json(OwnerInvitationStatus::asSelectArray());
    }
}
