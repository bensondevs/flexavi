<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class UserController extends Controller
{
    /**
     * Check if user email is used
     * 
     * @param Illuminate\Http\Request  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function checkEmailUsed(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $email = $request->input('email');
        if (db('users')->where('email', $email)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'This email has been taken by other user.',
            ], 409);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'This email is available.',
        ]);
    }

    /**
     * Get all User ID Card Types enums
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function allIdCardTypes()
    {
        $types = User::collectAllIdCardTypes();
        return response()->json($types);
    }
}
