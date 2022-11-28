<?php

namespace Tests\Unit\Repositories\Employee\EmployeeInvitationRepository;

use App\Repositories\Employee\EmployeeInvitationRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Repositories\Employee\EmployeeInvitationRepository
 *      To the tested repository class.
 */
class EmployeeInvitationRepositoryTest extends TestCase
{
    /**
     * Employee Invitation Repository instance container property.
     *
     * @var EmployeeInvitationRepository|null
     */
    private ?EmployeeInvitationRepository $employeeInvitationRepository = null;

    /**
     * Create or get employee invitation repository.
     *
     * @param bool $force
     * @return EmployeeInvitationRepository
     */
    protected function employeeInvitationRepository(bool $force = false): EmployeeInvitationRepository
    {
        if ($this->employeeInvitationRepository instanceof EmployeeInvitationRepository) {
            return $this->employeeInvitationRepository;
        }

        $employeeInvitationRepository = app(EmployeeInvitationRepository::class);
        return $this->employeeInvitationRepository = $employeeInvitationRepository;
    }

    /**
     * Ensure thr repository instance is encapsulated correctly.
     *
     * @test
     * @return void
     */
    public function it_does_not_crash_when_encapsulated(): void
    {
        $repository = $this->employeeInvitationRepository();
        $this->assertInstanceOf(EmployeeInvitationRepository::class, $repository);
    }
}
