<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;

use App\Repositories\UserRepository;

class UsersSeeder extends Seeder
{
	protected $user;

	public function __construct(UserRepository $user)
	{
		$this->user = $user;
	}

	public function randomRole()
	{
		return (['owner', 'employee'])[rand(0, 1)];
	}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$user = $this->user->save([
    		'fullname' => 'Flexavi Admin ',
    		'salutation' => 'Mr.',
    		'birth_date' => carbon()->now()->subYears(rand(20, 25)),
    		'id_card_type' => 'id_card',
    		'id_card_number' => rand(111, 999) . rand(111, 999) . rand(111, 999),
    		'phone' => '999999999999',
    		'address' => '11, A Road Name',
    		'profile_picture_url' => 'https://dummyimage.com/300/09f/fff.png',

    		'email' => 'admin@flexavi.nl',
    		'password' => 'admin',
    	]);
    	$user->assignRole('admin');

        for ($index = 0; $index < 100; $index++) {
        	$this->user->setModel(new User);
        	$this->user->save([
        		'fullname' => 'Flexavi User ' . ($index + 1),
        		'salutation' => 'Mr.',
        		'birth_date' => carbon()->now()->subYears(rand(20, 25)),
        		'id_card_type' => 'id_card',
        		'id_card_number' => rand(111, 999) . rand(111, 999) . rand(111, 999),
        		'phone' => '999999999999',
        		'address' => '11, A Road Name',
        		'profile_picture_url' => 'https://dummyimage.com/300/09f/fff.png',

        		'email' => 'user' . ($index + 1) . '@flexavi.nl',
        		'password' => 'user' . ($index + 1),
        	]);
        	$this->user->getModel()->assignRole($this->randomRole());
        }
    }
}
