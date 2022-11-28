<?php

namespace App\Repositories\Auths;

use App\Enums\Employee\EmploymentStatus;
use App\Jobs\{Auth\SendResetPasswordToken, SendMail};
use App\Mail\Auth\VerifyEmail;
use App\Models\{User\EmailVerification, User\User};
use App\Repositories\Base\BaseRepository;
use App\Services\Log\LogService;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\{Crypt, Gate};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new User());
    }

    /**
     * Register user using array with user data
     *
     * @param array $registerData
     * @return User|null
     */
    public function register(array $registerData): ?User
    {
        try {
            $user = $this->getModel();
            $user->fill($registerData);
            $user->unhashed_password = $registerData['password'];
            if (isset($registerData['profile_picture'])) {
                $user->profile_picture = $registerData['profile_picture'];
            }
            $user->save();
            $this->setModel($user);
            $this->setSuccess('Successfully register as user.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to register as user.', $error);
        }

        return $this->getModel();
    }

    /**
     * Send user email verification
     *
     * @return bool
     */
    public function sendEmailVerification(): bool
    {
        try {
            $user = $this->getModel();
            $sendJob = new SendMail(new VerifyEmail($user), $user->email);
            dispatch($sendJob);
            $this->setSuccess('Successfully send email verification.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to send email verification', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Verify email using encrypted code
     *
     * @param string $encryptedCode
     * @return EmailVerification|null
     */
    public function verifyEmail(string $encryptedCode): ?EmailVerification
    {
        try {
            $decryptedCode = Crypt::decryptString($encryptedCode);
            $verification = EmailVerification::findByCodeOrFail($decryptedCode);
            $verification->verify();
            $this->setSuccess('Successfully verify email address');

            return $verification;
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to verify email address.', $error);
        }

        return null;
    }

    /**
     * Execute API Login
     *
     * @param array $credentials
     * @return User|null
     * @see \Tests\Unit\Repositories\Auths\AuthRepository\LoginTest
     *      To the method unit tester class.
     */
    public function login(array $credentials): ?User
    {
        try {
            $email = $credentials['email'];
            $password = $credentials['password'];
            if (!($user = User::findByEmail($email))) {
                $this->setNotFound('User not found!');
                return null;
            }

            if (!$user->isPasswordMatch($password)) {
                $this->setUnprocessedInput('Password mismatch the record!');
                return null;
            }

            if (Gate::forUser($user)->allows('is-account-deleted')) {
                $this->setUnprocessedInput('Your account has been deleted, please contact your administrators!');
                return null;
            }

            if ($user->isEmployee() and !$user->hasCompany()) {
                $this->setUnprocessedInput('User is not assigned to any company!');
                return null;
            }

            if ($user->company->isEligibleForTrial()) {
                $company = $user->company;
                $company->startTrial();
            }

            $user->generateToken();

            Auth::login($user);

            $this->setModel($user);
            $this->setSuccess('Successfully login');

            LogService::make("user.login")
                ->by($user)
                ->on($user)
                ->write();
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError(
                'Failed to do login, there is something wrong and we don\' know yet',
                $error
            );
        }

        return $this->getModel();
    }

    /**
     * Execute user logout
     *
     * @return bool
     */
    public function logout(): bool
    {
        try {
            $user = $this->getModel();
            $user->tokens()->delete();
            $this->destroyModel();
            $this->setSuccess('Successfully logged out');
        } catch (QueryException $qe) {
            $this->setError('Failed to log out', $qe->getMessage());
        }

        return $this->returnResponse();
    }

    /**
     * Send reset password token to user email
     *
     * @return bool
     */
    public function sendResetPasswordToken()
    {
        try {
            $user = $this->getModel();
            if ($token = $user->resetPasswordToken) {
                $token->delete();
                $user->generateResetPasswordToken();
            }

            $job = new SendResetPasswordToken($user, $user->resetPasswordToken);
            dispatch($job);
            $this->setSuccess('Successfully send reset password token.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to send reset password token.', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Change user password using unhashed password
     *
     * @param string $password
     * @return User|null
     */
    public function changePassword(string $password)
    {
        try {
            $user = $this->getModel();
            $user->unhashed_password = $password;
            $user->save();
            $this->setModel($user);
            $this->setSuccess('Successfully change password.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to change password', $error);
        }

        return $this->getModel();
    }

    /**
     * Claim reset password token
     *
     * @return User|null
     */
    public function claimResetPasswordToken()
    {
        try {
            $user = $this->getModel();
            $user->resetPasswordToken()->delete();
            $this->setModel($user);
            $this->setSuccess('Successfully claim reset password token.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to claim reset password.', $error);
        }

        return $this->getModel();
    }
}
