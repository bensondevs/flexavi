<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class UserController extends Controller
{
    public function checkEmailUsed(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $email = $request->email;
        if (db('users')->where('email', $email)->count() > 0) {
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

    public function allIdCardTypes()
    {
        $types = User::collectAllIdCardTypes();
        return response()->json(['id_card_types' => $types]);
    }
}
