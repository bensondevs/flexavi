<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use Socialite;

use App\Models\User;
use App\Models\Customer;

use App\Repositories\EmployeeRepository;
use App\Repositories\Base\BaseRepository;

class AuthRepository extends BaseRepository
{
	private $customer;
	private $employee;
	private $owner;

	public function __construct()
	{
		$this->setInitModel(new User);
		$this->customer = new Customer();
		$this->employee = new EmployeeRepository;
	}

	public function register($registerData, $attachments = [])
	{
		try {
			$user = $this->getModel();
			$user->fill($registerData);
			$user->unhashed_password = $registerData['password'];
			$user->save();

			// Attachment exist
			if (isset($attachments['data'])) {
				$attachmentsData = $attachments['data'];

				if ($attachments['role'] == 'employee') {
					$employee = $this->employee->save([
						'user_id' => $user->id,
						'company_id' => $attachmentsData['company_id'],

						'title' => $attachmentsData['title'],
						'employee_type' => $attachmentsData['employee_type'],
						'employee_status' => $attachmentsData['employee_status'],
					]);
				} else if ($attachments['role'] == 'owner') {
					$owner = $this->owner->save([
						'user_id' => $user->id,
						'is_prime_owner' => false,
						'company_id' => $attachmentsData['company_id'],

						'bank_name' => $attachmentsData['bank_name'],
						'bic_code' => $attachmentsData['bic_code'],
						'bank_account' => $attachmentsData['bank_account'],
						'bank_holder_name' => $attachmentsData['bank_holder_name'],
					]);
				}
			}

			$this->setModel($user);

			$this->setSuccess('Successfully register as user.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to register as user.', 
				$qe->getMessage()
			);
		}

		return $this->getModel();
	}

	public function login(array $credentials)
	{
		try {
			// Collect credentials
			$email = $credentials['email'];
			$password = $credentials['password'];

			// Find user
			$user = $this->getModel()->where('email', $email)->first();
			if (! $user) {
				$this->setNotFound('User not found!');
				return null;
			}
			$this->setModel($user); // Found!

			// Check if password matched the record
			if (! hashCheck($password, $user->password)) {
				$this->setUnprocessedInput('Password mismatch the record!');
				return null;
			}

			// API Login Token
			$user->token = $user->createToken(time())->plainTextToken;

			$this->setModel($user);

			$this->setSuccess('Successfully login');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to do login, there is something wrong and we don\' know yet', 
				$qe->getMessage()
			);
		}

		return $this->getModel();
	}

	public function customerLogin(array $credentials)
	{
		try {
			$zipcode = $credentials['zipcode'];
			$houseNumber = $credentials['house_number'];

			$customer = $this->customer->where('zipcode', $zipcode)
				->where('house_number', $houseNumber)
				->first();

			if (! $customer) 
				return $this->setNotFound('Customer account not found.');

			$customer->token = $customer->createToken(time())->plainTextToken;
			$this->customer = $customer;

			$this->setSuccess('Successfully logged in as customer');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to login to customer.', 
				$qe->getMessage()
			);
		}

		return $this->status == 'success' ?
			$this->customer : null;
	}

	public function socialiteLogin($driver)
	{
		try {
			$socialiteUser = Socialite::driver($driver)
	            ->stateless()
	            ->user();
			$user = $this->getModel()
				->where('email', $socialiteUser->getEmail())
				->first();

			if (! $user) {
				return $this->setNotFound(
					'This user is not yet registered.'
				);
			}

			/*
				Login the found user
			*/
			$user->token = $user->createToken(time())->plainTextToken;
			$user->{$driver . 'Login'}->setCallbackResponse($socialiteUser);

			$this->setModel($user);

			$this->setSuccess('Successfully login.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to execute login from social media', 
				$qe->getMessage()
			);
		}

		return $this->getModel();
	}

	public function socialiteRegister($driver)
	{
		try {
			$socialiteUser = Socialite::driver($driver)
	            ->stateless()
	            ->user();

			$user = new User;
			$user->id = generateUUID();
			$user->fullname = $socialiteUser->getName();
			$user->email = $socialiteUser->getEmail();
			$user->profile_picture_url = $socialiteUser->getAvatar();

			$this->setModel($user);

			$this->setSuccess('Successfully do socialite register');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to register user through social media.', 
				$qe->getMessage()
			);
		}

		return $this->getModel();
	}

	public function logout()
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

	public function customerLogout(Customer $customer)
	{
		try {
			$customer->tokens()->delete();

			$this->setSuccess('Successfully logged out');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to log out', 
				$qe->getMessage()
			);
		}

		return $this->returnResponse();
	}
}
