<?php

namespace App\Services\Auth;

use App\Models\User\PasswordReset;
use App\Models\User\User;
use App\Repositories\User\PasswordResetRepository;
use App\Repositories\User\UserRepository;
use Exception;
use Illuminate\Http\JsonResponse;

class PasswordResetService
{
    /**
     * Password Reset Repository Class Container
     *
     * @var PasswordResetRepository
     */
    protected PasswordResetRepository $passwordResetRepository;

    /**
     * User Repository Class Container
     *
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * Service constructor method
     *
     * @param PasswordResetRepository $passwordResetRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        PasswordResetRepository $passwordResetRepository,
        UserRepository          $userRepository
    )
    {
        $this->passwordResetRepository = $passwordResetRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Create token for request reset password
     *
     * @param array $data
     * @return JsonResponse
     */
    public function createToken(array $data): JsonResponse
    {
        try {
            $this->passwordResetRepository->save($data);
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully to send code via ' . ucfirst($data['via'])
            ], 201);
        } catch (Exception $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send code via ' . ucfirst($data['via'])
            ], 500);
        }
    }

    /**
     * Reset Password
     *
     * @param PasswordReset $passwordReset
     * @param User $user
     * @param string $password
     * @return JsonResponse
     */
    public function resetPassword(PasswordReset $passwordReset, User $user, string $password): JsonResponse
    {
        try {
            $this->userRepository->setModel($user);
            $this->userRepository->changePassword($password);
            $passwordReset->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully change password.',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to change password.'
            ], $th->getCode());
        }
    }
}
