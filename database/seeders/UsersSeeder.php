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

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$user = $this->user->save([
    		'fullname' => 'Flexavi Admin ',
    		'birth_date' => carbon()->now()->subYears(rand(20, 25)),
    		'id_card_type' => 1,
    		'id_card_number' => rand(111, 999) . rand(111, 999) . rand(111, 999),
    		'phone' => '999999999999',
    		'profile_picture_path' => 'uploads/profile_pictures/20210503075156pp.jpeg',

    		'email' => 'admin@flexavi.nl',
    		'password' => 'admin',
    	]);
        $this->user->getModel()->assignRole('admin');

        for ($index = 0; $index < 50; $index++) {
        	$this->user->setModel(new User);
        	$this->user->save([
        		'fullname' => 'Flexavi Owner ' . ($index + 1),
        		'birth_date' => carbon()->now()->subYears(rand(20, 25)),
        		'id_card_type' => 1,
        		'id_card_number' => rand(111, 999) . rand(111, 999) . rand(111, 999),
        		'phone' => '999999999999',
        		'profile_picture_path' => 'uploads/profile_pictures/20210503075156pp.jpeg',

        		'email' => 'owner' . ($index + 1) . '@flexavi.nl',
        		'password' => 'owner' . ($index + 1),
        	]);
        	$this->user->getModel()->assignRole('owner');
        }

        for ($index = 0; $index < 20; $index++) {
            $this->user->setModel(new User);
            $user = $this->user->save([
                'fullname' => 'Flexavi Employee ' . ($index + 1),
                'birth_date' => carbon()->now()->subYears(rand(20, 25)),
                'id_card_type' => 1,
                'id_card_number' => rand(111, 999) . rand(111, 999) . rand(111, 999),
                'phone' => '999999999999',
                'profile_picture_path' => 'uploads/profile_pictures/20210503075156pp.jpeg',

                'email' => 'employee' . ($index + 1) . '@flexavi.nl',
                'password' => 'employee' . ($index + 1),
            ]);
            $this->user->getModel()->assignRole('employee');
        }
    }
}
