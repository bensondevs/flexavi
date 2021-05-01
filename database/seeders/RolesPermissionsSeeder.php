<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Repositories\PermissionRepository;

class RolesPermissionsSeeder extends Seeder
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

        // Create Permissions
        $permissionNames = [
            // User
            'view users',
            'create users',
            'edit users',
            'delete users',

            // Company
            'view companies',
            'create companies',
            'edit companies',
            'delete companies',
        ];
    }
}
