<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use Auth;
use Socialite;

use App\Repositories\Base\BaseRepository;

use App\Models\User;

class SocialiteAuthRepository extends BaseRepository
{
	private $driver;
	private $socialite;
	private $socialiteUser;

	public static function setDriver($driver)
	{
		$this->driver = $driver;
		$this->socialite = Socialite::driver($driver);
		return $this;
	}

	public function urlToVendor()
	{
		return $this->socialite->stateless()->redirect()->getTargetUrl();
	}

	public function recieveCallback()
	{
		return $this->socialiteUser = $this->socialite->stateless()->user();
	}

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

	public function disconnect(User $user, $driver)
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
