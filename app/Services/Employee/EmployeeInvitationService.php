<?php

namespace App\Services\Employee;

use App\Jobs\Employee\SendEmployeeInvitation;
use App\Models\Employee\EmployeeInvitation;
use App\Models\Invitation\RegisterInvitation;
use App\Repositories\Employee\EmployeeInvitationRepository;

class EmployeeInvitationService
{
    /**
     * Employee invitation repository instance container property.
     *
     * @var EmployeeInvitationRepository
     */
    private EmployeeInvitationRepository $employeeInvitationRepository;

	/**
	 * Create Employee Invitation Service Instance.
	 *
	 * @return void
	 */
	public function __construct(
        EmployeeInvitationRepository $employeeInvitationRepository
    ) {
		$this->employeeInvitationRepository = $employeeInvitationRepository;
	}

    /**
     * Send invitation to the user.
     *
     * @param array $invitationData
     * @return EmployeeInvitation
     */
    public function createAndSendInvitation(
        array $invitationData
    ): EmployeeInvitationRepository {
        // Create employee invitation instance
        $employeeInvitation = $this->employeeInvitationRepository
            ->createInvitation($invitationData);

        // Create register invitation instance
        RegisterInvitation::create([
            'invitationable_type' => get_class($employeeInvitation),
            'invitationable_id' => $employeeInvitation->id,
            'registration_code' => $employeeInvitation->registration_code,
            'expiry_time' => $employeeInvitation->expiry_time,
        ]);

        // Queue employee invitation send job
        $this->sendInvitation($employeeInvitation);

        $this->employeeInvitationRepository->setModel($employeeInvitation);
        return $this->employeeInvitationRepository;
    }

    /**
     * Send invitation to the invited employee by giving employee invitation model.
     *
     * @param EmployeeInvitation $employeeInvitation
     * @return void
     */
    public function sendInvitation(EmployeeInvitation $employeeInvitation): void
    {
        dispatch(new SendEmployeeInvitation($employeeInvitation));
    }
}
