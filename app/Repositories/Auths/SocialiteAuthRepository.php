<?php

namespace App\Repositories\Auths;

use App\Models\User\User;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;

class SocialiteAuthRepository extends BaseRepository
{
    /**
     * Current selected social media driver of socialite
     *
     * @var string|null
     */
    private $driver;

    /**
     * Created socialite class to handle the actions
     *
     * @var Socialite|null
     */
    private $socialite;

    /**
     * Acquired socialite user instance from API
     *
     * @var SocialiteUser|null
     */
    private $socialiteUser;

    /**
     * Set used driver of socialite.
     * This will select which social media is going to be used
     *
     * @param string  $driver
     * @return SocialiteAuthRepository
     */
    public function setDriver(string $driver)
    {
        $this->driver = $driver;
        $this->socialite = Socialite::driver($driver);

        return $this;
    }

    /**
     * Get url to loggin to vendor login service
     *
     * @return string
     */
    public function urlToVendor()
    {
        if (is_null($this->socialite)) {
            return '';
        }
        return $this->socialite
            ->stateless()
            ->redirect()
            ->getTargetUrl();
    }

    /**
     * Receive callback response of socialite user
     *
     * @return SocialiteUser|null
     */
    public function recieveCallback()
    {
        if (is_null($this->socialite)) {
            return null;
        }
        return $this->socialiteUser = $this->socialite->stateless()->user();
    }

    /**
     * Register user using socialite data
     *
     * @param array  $registerData
     * @return User
     */
    public function register(array $registerData)
    {
        if (!$this->socialiteUser) {
            return abort(
                500,
                'An error occured, please relogin to make sure we get the user data from vendor social media.'
            );
        }
        $socialiteUser = $this->socialiteUser;
        if (
            $user = User::findBySocialId($this->driver, $socialiteUser->getId())
        ) {
            return abort(403, 'The user is already registered.');
        }
        if (User::checkEmailUsed($socialiteUser->getEmail())) {
            return abort(
                422,
                'This user has been registered with other method. Please directly connect through your setting dashboard.'
            );
        }
        try {
            $user = new User($registerData);
            $user->email = $socialiteUser->getEmail();
            $user->profile_picture_url = $socialiteUser->getAvatar();
            $user->save();
            $this->setSuccess('Successfully create user.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to create user.', $error);
        }

        return $user;
    }

    /**
     * Login the acquired socialite user
     *
     * @param bool  $remember
     * @return User
     */
    public function login(bool $remember = false)
    {
        if (!$this->socialiteUser) {
            return abort(
                500,
                'An error occured, please relogin to make sure we get the user data from vendor social media.'
            );
        }
        if (
            !($user = User::findBySocialId(
                $this->driver,
                $this->socialiteUser->getId()
            ))
        ) {
            return abort(
                404,
                'Not registered yet, please use conventional login or other connected social media thats connected to the account.'
            );
        }
        Auth::login($user, $remember);
        if (is_null(auth()->user())) {
            abort(500, 'Failed to authenticate user.');
        }
        $this->setSuccess('Successfully logged in.');

        return $user;
    }

    /**
     * Connect social media to user
     *
     * @param User  $user
     * @return User|null
     */
    public function connect(User $user)
    {
        if ($user->{$this->driver . '_id'}) {
            abort(
                422,
                'Already connected to social media authentication method.'
            );
        }
        if (is_null($this->socialiteUser)) {
            return null;
        }
        try {
            $user->{$this->driver . '_id'} = $this->socialiteUser->getId();
            $user->save();
            $this->setSuccess('Successfully connect account to social media.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError(
                'Failed to connect account to social media.',
                $error
            );
        }

        return $user;
    }

    /**
     * Disconnect social media from user
     *
     * @param User  $user
     * @param string  $driver
     * @return User
     */
    public function disconnect(User $user, string $driver)
    {
        try {
            $user->{$driver . '_id'} = null;
            $user->save();
            $this->setSuccess(
                'Successfully disconnect account from social media.'
            );
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError(
                'Failed to disconnect account from social media.',
                $error
            );
        }

        return $user;
    }
}
