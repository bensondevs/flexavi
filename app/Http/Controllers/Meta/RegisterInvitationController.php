<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\RegisterInvitation;

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
