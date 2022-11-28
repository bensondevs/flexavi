<?php

namespace Database\Seeders;

use App\Models\Employee\Employee;
use App\Repositories\Permission\PermissionRepository;
use Illuminate\Database\Seeder;

/**
 * @deprecated
 */
class EmployeePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $permissionNames = app(PermissionRepository::class)
            ->permissionNames();

        foreach (Employee::with('user')->has('user')->get() as $employee) {
            $user = $employee->user ?: $employee->load(['user'])->user;

            $givenPermissions = collect($permissionNames)
                ->take(rand(1, count($permissionNames) - 1));
            $user->syncPermissions($givenPermissions);
        }
    }
}
