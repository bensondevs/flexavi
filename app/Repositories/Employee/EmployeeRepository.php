<?php

namespace App\Repositories\Employee;

use App\Jobs\SendMail;
use App\Mail\Employee\EmployeePasswordReseted;
use App\Models\Appointment\AppointmentEmployee;
use App\Models\Employee\Employee;
use App\Models\User\User;
use App\Repositories\Base\BaseRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EmployeeRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new Employee());
    }

    /**
     * Get inviteable employees
     *
     * @param array $options
     * @return Collection|LengthAwarePaginator
     */
    public function inviteables(array $options = [])
    {
        $options['wheres'][] = [
            'column' => 'user_id',
            'value' => null,
        ];

        return $this->all($options);
    }

    /**
     * Get appointment employees
     *
     * @param array $options
     * @param bool $pagination
     * @return Collection|LengthAwarePaginator
     */
    public function appointmentEmployees(
        array $options = [],
        bool $pagination = false
    ) {
        $this->setModel(new AppointmentEmployee());

        return $this->all($options, $pagination);
    }

    /**
     * Save employee
     *
     * @param array $employeeData
     * @return Employee|null
     */
    public function save(array $employeeData): ?Employee
    {
        try {
            $employee = $this->getModel();
            $employee->fill($employeeData);
            if (isset($employeeData['contract_file'])) {
                $employee->contract_file = $employeeData['contract_file'];
            }
            $employee->save();

            if (isset($employeeData['fullname'])) {
                User::whereId($employee->user_id)->update([
                    'fullname' => $employeeData['fullname'],
                ]);
            }

            if (isset($employeeData['birth_date'])) {
                User::whereId($employee->user_id)->update([
                    'birth_date' => $employeeData['birth_date'],
                ]);
            }

            $this->setModel($employee);
            $this->setSuccess('Successfully save employee data.');
        } catch (QueryException $qe) {
            $queryError = $qe->getMessage();
            $this->setError('Failed to save employee data.', $queryError);
        }

        return $this->getModel();
    }

    /**
     * Reset employee password.
     *
     * @param string $password
     * @return Employee
     */
    public function resetPassword(string $password): Employee
    {
        try {
            $employee = $this->getModel();
            $user = $employee->user;

            $userRepository = app(UserRepository::class);
            $userRepository->setModel($user);
            $userRepository->changePassword($password);

            $mailable = new EmployeePasswordReseted($employee);
            dispatch(new SendMail($mailable, $user->email))->afterResponse();

            $this->setSuccess('Successfully reset employee password.');
        } catch (QueryException $queryException) {
            $error = $queryException->getMessage();
            $this->setError('Failed to reset employee password.', $error);
        }

        return $this->getModel();
    }

    /**
     * Delete employee soft or hard
     *
     * @param  bool  $force
     * @return bool
     */
    public function delete($force = false)
    {
        try {
            $employee = $this->getModel();
            $force ? $employee->forceDelete() : $employee->delete();
            $this->destroyModel();
            $this->setSuccess('Successfully delete employee.');
        } catch (QueryException $qe) {
            $queryError = $qe->getMessage();
            $this->setError('Failed to delete employee.', $queryError);
        }

        return $this->returnResponse();
    }

    /**
     * Restore Employee from trash
     *
     * @return Employee|null
     */
    public function restore()
    {
        try {
            $employee = $this->getModel();
            $employee->restore();
            $this->setModel($employee);
            $this->setSuccess('Successfully restore employee.');
        } catch (QueryException $qe) {
            $queryError = $qe->getMessage();
            $this->setError('Failed to restore employee', $queryError);
        }

        return $this->getModel();
    }

    /**
     * Set employee image
     *
     * @param mixed $imageFile
     * @return Employee|null
     */
    public function setImage($imageFile)
    {
        try {
            $employee = $this->getModel();
            $user = $employee->user;
            $user->profile_picture = $imageFile;
            $user->save();
            $this->setModel($employee);
            $this->setSuccess('Successfully set image employee.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to set employee image. ', $error);
        }

        return $this->getModel();
    }
}
