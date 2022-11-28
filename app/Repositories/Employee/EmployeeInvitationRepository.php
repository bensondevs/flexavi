<?php

namespace App\Repositories\Employee;

use App\Enums\Employee\EmployeeType;
use App\Enums\Employee\EmploymentStatus;
use App\Enums\EmployeeInvitation\EmployeeInvitationStatus;
use App\Models\Employee\EmployeeInvitation;
use App\Models\User\User;
use App\Repositories\Base\BaseRepository;
use App\Repositories\User\UserRepository;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\QueryException;

/**
 * @see \Tests\Unit\Repositories\Employee\EmployeeInvitationRepository\EmployeeInvitationRepositoryTest
 *      To the repository class unit tester class.
 */
class EmployeeInvitationRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new EmployeeInvitation());
    }

    /**
     * Invite employee to register to the application
     *
     * @param string $email
     * @param array $employeeData
     * @param mixed $expiryTime
     * @return EmployeeInvitation|null
     */
    public function inviteEmployee(
        string $email,
        array $employeeData,
        mixed $expiryTime = null
    ): ?EmployeeInvitation {
        $invitationData = [
            'invited_email' => $email,
            'expiry_time' => $expiryTime ?: now()->addDays(3),
        ];
        $data = array_merge($invitationData, $employeeData);

        return $this->createInvitation($data);
    }

    /**
     * Send invitation to the pre-registered user
     *
     * @param array $invitationData
     * @return EmployeeInvitation|null
     */
    public function createInvitation(array $invitationData): ?EmployeeInvitation
    {
        try {
            $invitation = $this->getModel();

            // Set the invitation data at once
            $invitation->fill($invitationData);
            $invitation->save();

            $this->setModel($invitation);
            $this->setSuccess('Your invitation has been sent to employee.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to invite user to register', $error);
        }

        return $this->getModel();
    }

    /**
     * Set used employee invitation
     *
     * @return EmployeeInvitation|null
     */
    public function setUsed(): ?EmployeeInvitation
    {
        try {
            $invitation = $this->getModel();
            $invitation->status = EmployeeInvitationStatus::Used;
            $invitation->save();
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to handle invitation fulfillment.', $error);
        }

        return $this->getModel();
    }

    /**
     * Cancel employee invitation
     *
     * @return bool
     */
    public function cancel(): bool
    {
        try {
            $invitation = $this->getModel();

            $invitation->status = EmployeeInvitationStatus::Cancelled;
            $invitation->cancelled_at = now()->toDateTimeString();
            $invitation->forceDelete();

            $this->destroyModel();
            $this->setSuccess('Successfully cancel invitation.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to handle invitation fulfillment.', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Handle invitation using process
     *
     * @return EmployeeInvitation|null
     * @see \Tests\Unit\Repositories\Employee\EmployeeInvitationRepository\HandleInvitationFulfilledTest
     *      To the method unit tester class.
     */
    public function handleInvitationFulfilled(): ?EmployeeInvitation
    {
        try {
            $invitation = $this->getModel();

            // Assign invited user as employee
            $user = User::whereRegistrationCode($invitation->registration_code)->first();
            if (is_null($user)) {
                abort(500, 'The user does not found.');
            }
            $user->syncRoles('employee');

            // Assign permissions given in the invitation
            $givenPermissions = $invitation->permissions;
            $user->syncPermissions($givenPermissions);

            // Set the employee data according to the invitation
            $employee = $invitation->role_model;
            $employee['title'] = $invitation->title ?: 'Employee';
            $employee['company_id'] = $invitation->company_id;
            $employee['employee_type'] = $invitation->role;
            $employee['contract_file_path'] = $invitation->contract_file_path;
            $employee['user_id'] = $user->id;
            $employee->save();

            // Set the invitation status as used
            $invitation->used();

            $this->setModel($invitation);

            $this->setSuccess('Successfully handle invitation fulfillment.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to handle invitation fulfillment.', $error);
        }

        return $this->getModel();
    }

    /**
    * Accept an invitation
    *
    * @return EmployeeInvitation|null
    */
    public function accept(): ?EmployeeInvitation
    {
        try {
            $invitation = $this->getModel();
            $permissionNames = $invitation->permissions;

            // invited user instance
            $user = User::query()->firstOrNew([
                'registration_code' => $invitation->registration_code
            ])->fill([
                    'fullname' => $invitation->name ,
                    'email' => $invitation->invited_email ,
                    'phone' => $invitation->phone ,
                    'birth_date' => $invitation->birth_date ,
                ])
                ->assignRole('employee')
                ->syncPermissions($permissionNames);
            $user->save();

            // invited employee instance
            $employee = ($invitation->role_model)->fill([
                'company_id' => $invitation->company_id,
                'user_id' => $user->id,
                'title' => $invitation->title,
                'employee_type' => $invitation->employee_type ?? EmployeeType::Roofer,
                'employement_status' => $invitation->employment_status ?? EmploymentStatus::Active,
                'contract_file_path' => $invitation->contract_file_path,
            ]);
            $employee->save();

            $invitation->status = EmployeeInvitationStatus::Used;
            $invitation->save();
            $this->setSuccess('Successfully handle invitation fulfillment.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to handle invitation fulfillment.', $error);
        }

        return $this->getModel();
    }
}
