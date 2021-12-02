<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Repositories\Base\BaseRepository;

use App\Models\User;

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
	 * @var \Laravel\Socialite\Facades\Socialite|null
	 */
	private $socialite;

	/**
	 * Acquired socialite user instance from API
	 * 
	 * @var Laravel\Socialite\Two\User
	 */
	private $socialiteUser;

	/**
	 * Set used driver of socialite.
	 * This will select which social media is going to be used
	 * 
	 * @param string  $driver
	 * @return $this
	 */
	public static function setDriver(string $driver)
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
		return $this->socialite
			->stateless()
			->redirect()
			->getTargetUrl();
	}

	/**
	 * Receive callback response of socialite user
	 * 
	 * @return Laravel\Socialite\Two\User
	 */
	public function recieveCallback()
	{
		return $this->socialiteUser = $this->socialite->stateless()->user();
	}

	/**
	 * Register user using socialite data
	 * 
	 * @param array  $registerData
	 * @return \App\Models\User|abort 500|abort 403|abort 422
	 */
	public function register(array $registerData)
	{
		if (! $this->socialiteUser) {
			return abort(500, 'An error occured, please relogin to make sure we get the user data from vendor social media.');
		}

		$socialiteUser = $this->socialiteUser;
		if ($user = User::findBySocialId($this->driver, $socialiteUser->getId())) {
			return abort(403, 'The user is already registered.');
		}

		if (User::checkEmailUsed($socialiteUser->getEmail())) {
			return abort(422, 'This user has been registered with other method. Please directly connect through your setting dashboard.');
		}

		try {
			$user = new User($registerData);
			$user->email = $socialiteUser->getEmail();
			$user->profile_picture_url = $socialiteUser->getAvatar();
			$user->save();
			
			$this->setSuccess('Successfully create user.');	
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			return $this->setError('Failed to create user.', $error);
		}

		return $user;
	}

	/**
	 * Login the acquired socialite user
	 * 
	 * @param bool  $remember
	 * @return \App\Models\User|abort 500|abort 403|abort 422
	 */
	public function login(bool $remember = false)
	{
		if (! $this->socialiteUser) {
			return abort(500, 'An error occured, please relogin to make sure we get the user data from vendor social media.');
		}

		if (! $user = User::findBySocialId($this->driver, $socialiteUser->getId())) {
			return abort(404, 'Not registered yet, please use conventional login or other connected social media thats connected to the account.');
		}

		if (! Auth::login($user, $remember)) {
			return abort(500, 'Failed to authenticate user.');
		}

		$this->setSuccess('Successfully logged in.');

		return $user;
	}

	/**
	 * Connect social media to user
	 * 
	 * @param \App\Models\User  $user
	 * @return \App\Models\User
	 */
	public function connect(User $user)
	{
		if ($user->{$this->driver . '_id'}) {
			return abort(422, 'Already connected to social media authentication method.');
		}

		try {
			$user->{$this->driver . '_id'} = $this->socialiteUser->getId();
			$user->save();
			
			$this->setSuccess('Successfully connect account to social media.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to connect account to social media.', $error);
		}

		return $user;
	}

	/**
	 * Disconnect social media from user
	 * 
	 * @param \App\Models\User  $user
	 * @param string  $driver
	 * @return \App\Models\User
	 */
	public function disconnect(User $user, string $driver)
	{
		try {
			$user->{$driver . '_id'} = null;
			$user->save();

			$this->setSuccess('Successfully disconnect account from social media.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to disconnect account from social media.', $error);
		}

		return $user;
	}
}
