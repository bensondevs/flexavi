<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Repositories\PermissionRepository;

class RolesSeeder extends Seeder
{
	protected $permission;

	public function __construct(PermissionRepository $permission)
	{
		$this->permission = $permission;
	}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->permission->createRole('admin');
        $this->permission->createRole('owner');
        $this->permission->createRole('employee');
        $this->permission->createRole('customer');
    }
}
