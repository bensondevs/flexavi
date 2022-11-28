<?php

namespace Database\Seeders;

use App\Models\Permission\Role;
use App\Enums\Role as RoleType;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        foreach (RoleType::getValues() as $roleType) {
            if (Role::whereName($roleType)->doesntExist()) {
                Role::create(['name' => $roleType]);
            }
        }
    }
}
