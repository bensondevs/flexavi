<?php

namespace App\Http\Controllers\Api\Auths;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\ForgotPasswords\{FindAccountRequest,
    ResetPasswordRequest,
    SendResetCodeRequest,
    ValidateTokenRequest
};
use App\Http\Resources\Auth\ForgotPasswordResource;
use App\Repositories\User\PasswordResetRepository;
use App\Services\Auth\PasswordResetService;
use Illuminate\Http\JsonResponse;

class ForgotPasswordController extends Controller
{
    /**
     * Password Reset Repository Class Container
     *
     * @var PasswordResetRepository
     */
    private PasswordResetRepository $passwordResetRepository;

    /**
     * Password reset service class container
     *
     * @var PasswordResetService
     */
    private PasswordResetService $passwordResetService;

    /**
     * Controller constructor method
     *
     * @param PasswordResetRepository $passwordResetRepository
     * @param PasswordResetService $passwordResetService
     */
    public function __construct(
        PasswordResetRepository $passwordResetRepository,
        PasswordResetService    $passwordResetService
    )
    {
        $this->passwordResetRepository = $passwordResetRepository;
        $this->passwordResetService = $passwordResetService;
    }

    /**
     * Find Account
     *
     * @param FindAccountRequest $request
     * @return JsonResponse
     */
    public function findAccount(FindAccountRequest $request): JsonResponse
    {
        $account = $request->getCurrentUser();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully get a account of : ' . $request->email,
            'user' => new ForgotPasswordResource($account)
        ]);
    }

    /**
     * Send reset code
     *
     * @param SendResetCodeRequest $request
     * @return JsonResponse
     */
    public function sendResetCode(SendResetCodeRequest $request): JsonResponse
    {
        $this->passwordResetRepository->save($request->passwordResetData());
        return apiResponse($this->passwordResetRepository);
    }

    /**
     * Validate token of reset password
     *
     * @param ValidateTokenRequest $request
     * @return JsonResponse
     */
    public function validateToken(ValidateTokenRequest $request): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Token valid.',
            'password_reset' => $request->getResetPasswordToken()
        ], 200);
    }

    /**
     * Reset password
     *
     * @param ResetPasswordRequest $request
     * @return JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        return $this->passwordResetService->resetPassword(
            $request->getResetPasswordToken(),
            $request->getCurrentUser(),
            $request->password
        );
    }
}
