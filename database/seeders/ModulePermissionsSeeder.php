<?php

namespace Database\Seeders;

use App\Models\Permission\Module;
use App\Models\Permission\ModulePermission;
use App\Models\Permission\Permission;
use App\Models\Permission\Role;
use Illuminate\Database\Seeder;

class ModulePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->seedOwnerModulePermissions();

        $this->seedEmployeeModulePermissions();
    }

    /**
     * Seed owner module permissions.
     *
     * @return void
     */
    private function seedOwnerModulePermissions(): void
    {
        $rawModules = [];
        $rawPermissions = [];
        $role = Role::where('name', 'owner')->first();
        $data = [
            [
                'module_name' => 'Company Access',
                'module_description' => 'Company Details & Subscription',
                'permissions' => [
                    'register companies',
                    'edit companies',
                    'manage companies',
                    'close companies',
                    'purchase subscriptions',
                ],
            ],
            [
                'module_name' => 'Employee Access',
                'module_description' => 'Create, Edit, Delete, Restore',
                'permissions' => [
                    'view any employees',
                    'view employees',
                    'create employees',
                    'edit employees',
                    'delete employees',
                    'restore employees',
                    'force delete employees',
                ],
            ],
            [
                'module_name' => 'Customer Access',
                'module_description' => 'Create, Edit, Delete, Restore',
                'permissions' => [
                    'view any customers',
                    'view customers',
                    'create customers',
                    'edit customers',
                    'delete customers',
                    'restore customers',
                    'force delete customers',
                ],
            ],
            [
                'module_name' => 'Work Contract Access',
                'module_description' => 'Create, Edit, Delete, Restore',
                'permissions' => [
                    'view any work contracts',
                    'view work contracts',
                    'create work contracts',
                    'edit work contracts',
                    'delete work contracts',
                    'restore work contracts',
                    'force delete work contracts',
                    'nullify work contracts',
                ],
            ],
            [
                'module_name' => 'Quotation Access',
                'module_description' => 'Create, Edit, Delete, Restore',
                'permissions' => [
                    'view any quotations',
                    'view quotations',
                    'create quotations',
                    'edit quotations',
                    'delete quotations',
                    'restore quotations',
                    'force delete quotations'
                ],
            ],
            [
                'module_name' => 'Invoice Access',
                'module_description' => 'Create, Edit, Delete, Restore',
                'permissions' => [
                    'view any invoices',
                    'view invoices',
                    'create invoices',
                    'edit invoices',
                    'delete invoices',
                    'restore invoices',
                    'force delete invoices',
                ],
            ],
            [
                'module_name' => 'Owner Access',
                'module_description' => 'Create, Edit, Delete, Restore',
                'permissions' => [
                    'view any owners',
                    'view owners',
                    'edit owners',
                    'delete owners',
                    'restore owners',
                    'force delete owners',
                    'send owner register invitation',
                    'cancel employee invitations',
                ],
            ],
        ];

        $this->insertModulePermissions($data, $role, $rawModules, $rawPermissions);
    }

    /**
     * Seed employee module permissions.
     *
     * @return void
     */
    public function seedEmployeeModulePermissions(): void
    {
        $rawModules = [];
        $rawPermissions = [];
        $role = Role::where('name', \App\Enums\Role::Employee)->first();
        $data = [
            /*[
                'module_name' => 'Company Access',
                'module_description' => 'Company Details & Subscription',
                'permissions' => [
                    'register companies',
                    'edit companies',
                    'manage companies',
                    'close companies',
                    'purchase subscriptions',
                ],
            ],*/
            /*[
                'module_name' => 'Employee Access',
                'module_description' => 'Create, Edit, Delete, Restore',
                'permissions' => [
                    'view any employees',
                    'view employees',
                    'create employees',
                    'edit employees',
                    'delete employees',
                    'restore employees',
                    'force delete employees',
                ],
            ],*/
            [
                'module_name' => 'Customer Access',
                'module_description' => 'Create, Edit, Delete, Restore',
                'permissions' => [
                    'view any customers',
                    'view customers',
                    'create customers',
                    'edit customers',
                    'delete customers',
                    'restore customers',
                    'force delete customers',
                ],
            ],
            [
                'module_name' => 'Work Contract Access',
                'module_description' => 'Create, Edit, Delete, Restore',
                'permissions' => [
                    'view any work contracts',
                    'view work contracts',
                    'create work contracts',
                    'edit work contracts',
                    'delete work contracts',
                    'restore work contracts',
                    'force delete work contracts',
                    'nullify work contracts',
                ],
            ],
            [
                'module_name' => 'Quotation Access',
                'module_description' => 'Create, Edit, Delete, Restore',
                'permissions' => [
                    'view any quotations',
                    'view quotations',
                    'create quotations',
                    'edit quotations',
                    'delete quotations',
                    'restore quotations',
                    'force delete quotations'
                ],
            ],
            [
                'module_name' => 'Invoice Access',
                'module_description' => 'Create, Edit, Delete, Restore',
                'permissions' => [
                    'view any invoices',
                    'view invoices',
                    'create invoices',
                    'edit invoices',
                    'delete invoices',
                    'restore invoices',
                    'force delete invoices',
                ],
            ],
            /*[
                'module_name' => 'Owner Access',
                'module_description' => 'Create, Edit, Delete, Restore',
                'permissions' => [
                    'view any owners',
                    'view owners',
                    'edit owners',
                    'delete owners',
                    'restore owners',
                    'force delete owners',
                    'send owner register invitation',
                    'cancel employee invitations',
                ],
            ],*/
        ];

        $this->insertModulePermissions($data, $role, $rawModules, $rawPermissions);
    }

    /**
     * @param array $data
     * @param $role
     * @param array $rawModules
     * @param array $rawPermissions
     * @return void
     */
    private function insertModulePermissions(array $data, $role, array $rawModules, array $rawPermissions): void
    {
        foreach ($data as $row) {
            $moduleId = generateUuid();
            $rawModules[] = [
                'id' => $moduleId,
                'module_name' => $row['module_name'],
                'module_description' => $row['module_description'],
                'role_id' => $role->id,
                'created_at' => now(),
                'updated_at' => now()
            ];

            foreach ($row['permissions'] as $permission) {
                $rawPermissions[] = [
                    'id' => generateUuid(),
                    'module_id' => $moduleId,
                    'permission_id' => Permission::findByName($permission)->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        foreach (array_chunk($rawModules, 50) as $rawModulesChunk) {
            Module::insert($rawModulesChunk);
        }

        foreach (array_chunk($rawPermissions, 50) as $rawPermissionsChunk) {
            ModulePermission::insert($rawPermissionsChunk);
        }
    }
}
