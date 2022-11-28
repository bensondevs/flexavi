<?php

namespace Tests\Unit\Services\Permission\PermissionService;

use App\Services\Permission\PermissionService;
use Tests\TestCase;

/**
 * @see \App\Services\Permission\PermissionService
 *      To the tested service class.
 * @see https://app.clickup.com/t/357ad06
 *      To view tickets when they were created
 */
class PermissionServiceTest extends TestCase
{
    /**
     * Service instance container property.
     *
     * @var ?PermissionService
     */
    private ?PermissionService $service = null;

    /**
     * Test basic for making sure the class is not crashing.
     *
     * @test
     * @return void
     */
    public function it_does_not_have_stupid_error(): void
    {
        $this->assertInstanceOf(
            PermissionService::class,
            $this->permissionService()
        );
    }

    /**
     * Create or get service instance
     *
     * @param bool $force
     * @return PermissionService
     */
    protected function permissionService(bool $force = false): PermissionService
    {
        if ($this->service instanceof PermissionService and !$force) {
            return $this->service;
        }

        return $this->service = new PermissionService();
    }
}
