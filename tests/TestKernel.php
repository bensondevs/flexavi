<?php

namespace Tests;

use Database\Seeders\{ModulePermissionsSeeder, PermissionsSeeder, RolesSeeder};
use Spatie\Permission\PermissionRegistrar;

trait TestKernel
{
    /**
     * Define preferred date format for data testing
     *
     * @var string
     */
    public string $preferedDateFormat = 'Y-m-d H:i:s';

    /**
     * Setup test
     *
     * @return void
     */
    public function setUp(): void
    {
        // include all the normal setUp operations
        parent::setUp();

        // register all the roles and permissions
        $this->app->make(PermissionRegistrar::class)->registerPermissions();

        // run data seeders
        $this->setUpSeeders();
    }

    /**
     * Setup data seeders for test
     *
     * @return void
     */
    public function setUpSeeders(): void
    {
        // no seeders
    }
}
