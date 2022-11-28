<?php

namespace App\Repositories\User;

use App\Models\User\PasswordReset;
use App\Models\User\User;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class UserRepository extends BaseRepository
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
     * Set profile picture of the user
     *
     * @param mixed $pictureFile
     * @return User|null
     */
    public function setProfilePicture($pictureFile): ?User
    {
        try {
            $user = $this->getModel();
            $user->profile_picture = $pictureFile;
            $user->save();
            $this->setModel($user);
            $this->setSuccess('Successfully set user profile picture.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to set user profile picture.', $error);
        }

        return $this->getModel();
    }

    /**
     * Save user by supplied array input
     *
     * @param array $userData
     * @param bool $hashPassword
     * @return User|null
     */
    public function save(array $userData, bool $hashPassword = true): ?User
    {
        try {
            $user = $this->getModel();
            $user->fill($userData);
            if ($hashPassword and isset($userData['password'])) {
                $user->unhashed_password = $userData['password'];
            }
            $user->save();
            $this->setModel($user);
            $this->setSuccess('Successfully save user data.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save user data', $error);
        }

        return $this->getModel();
    }

    /**
     * Change user password
     *
     * @param string $password
     * @return User|null
     */
    public function changePassword(string $password): ?User
    {
        try {
            $user = $this->getModel();
            $user->unhashed_password = $password;
            $user->save();
            $this->setModel($user);
            PasswordReset::whereEmail($user->email)->delete();
            $this->setSuccess('Successfully change password.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to change user password.', $error);
        }

        return $this->getModel();
    }

    /**
     * Delete user
     *
     * @param bool $force
     * @return bool
     */
    public function delete(bool $force = false): bool
    {
        try {
            $user = $this->getModel();
            $delete = $force ? $user->forceDelete() : $user->delete();
            $this->destroyModel();
            $this->setSuccess('Successfully delete user data.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to delete user data.', $error);
        }

        return $this->returnResponse();
    }
}
