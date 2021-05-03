<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Employee;

use App\Repositories\UserRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\EmployeeRepository;

class EmployeesSeeder extends Seeder
{
	private $user;
	private $company;
	private $employee;

	public function __construct(
		UserRepository $user,
		CompanyRepository $company,
		EmployeeRepository $employee
	)
	{
		$this->user = $user;
		$this->company = $company;
		$this->employee = $employee;
	}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = $this->company->all()->toArray();
        $users = $this->user->hasRole('employee');

        foreach ($users as $key => $user) {
        	$this->employee->save([
        		'user_id' => $user->id,
        		'company_id' => $companies[rand(0, (count($companies) - 1))]['id'],
        		'title' => 'Employee Title',
        		'employee_type' => (['administrative', 'roofers'])[rand(0, 1)],
        		'employee_status' => (['active', 'inactive', 'fired'])[rand(0, 2)],
        		'photo_url' => 'https://dummyimage.com/300/09f/fff.png',
        	]);
        	$this->employee->setModel(new Employee);
        }
    }
}
