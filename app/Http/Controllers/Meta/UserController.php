<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use App\Http\Requests\Meta\User\{CheckEmailUsedRequest, HasCompanyRequest};
use App\Models\User\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Check if user has company
     *
     * @param HasCompanyRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Meta\UserTest::test_user_has_company()
     *      To the feature test
     */
    public function hasCompany(HasCompanyRequest $request): JsonResponse
    {
        try {
            $id = $request->input('id');
            $email = $request->input('email');
            $phone = $request->input('phone');
            $user = User::where('id', $id)
                ->orWhere('email', $email)
                ->orWhere('phone', $phone)
                ->first();
            return response()->json(['has_company' => boolval($user->company)]);
        } catch (\Exception) {
            return response()->json(['has_company' => false]);
        }
    }


    /**
     * Check if user email is used
     *
     * @param CheckEmailUsedRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Meta\UserTest::test_check_if_email_used()
     *      To the feature test
     */
    public function checkEmailUsed(CheckEmailUsedRequest $request): JsonResponse
    {
        $email = $request->email;
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
}
