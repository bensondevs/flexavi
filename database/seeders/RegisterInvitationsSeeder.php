<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\RegisterInvitation;

class RegisterInvitationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$rawInvitations = [];
        for ($index = 0; $index < 100; $index++) {
        	array_push($rawInvitations, [
        		'registration_code' => 'register' . ($index + 1),
        		'invited_email' => 'register' . ($index + 1) . '@flexavi.com',
				'expiry_time' => carbon()->now()->addYears(100),
        	]);
        }
        RegisterInvitation::insert($rawInvitations);
    }
}
