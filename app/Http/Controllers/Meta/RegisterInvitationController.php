<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use App\Models\Invitation\RegisterInvitation;

class RegisterInvitationController extends Controller
{
    /**
     * Get all register invitation statuses enums
     *
     * @return Illuminate\Support\Facades\Response
     */
    public function allStatuses()
    {
        $statuses = RegisterInvitation::collectAllStatuses();
        return response()->json($statuses);
    }
}
