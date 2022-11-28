<?php

namespace App\Http\Controllers\Api\Company\Appointments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Appointments\Employees\{PopulateAppointmentEmployeesRequest as PopulateRequest};
use App\Http\Requests\Company\Appointments\Employees\AssignAppointmentEmployeeRequest as AssignEmployeeRequest;
use App\Http\Requests\Company\Appointments\Employees\UnassignAppointmentEmployeeRequest as UnassignEmployeeRequest;
use App\Http\Resources\Appointment\AppointmentEmployeeResource;
use App\Repositories\{Appointment\AppointmentRepository, Employee\EmployeeRepository};
use Illuminate\Http\JsonResponse;

class AppointmentEmployeeController extends Controller
{
    /**
     * Employee repository class container
     *
     * @var EmployeeRepository
     */
    private EmployeeRepository $employee;

    /**
     * Appointment repository class container
     *
     * @var AppointmentRepository
     */
    private AppointmentRepository $appointment;

    /**
     * Controller constructor method
     *
     * @param EmployeeRepository $employee
     * @param AppointmentRepository $appointment
     * @return void
     */
    public function __construct(
        EmployeeRepository    $employee,
        AppointmentRepository $appointment
    )
    {
        $this->employee = $employee;
        $this->appointment = $appointment;
    }

    /**
     * Populate appointment employees
     *
     * @param PopulateRequest $request
     * @return JsonResponse
     */
    public function appointmentEmployees(PopulateRequest $request): JsonResponse
    {
        $options = $request->options();

        $appointmentEmployees = $this->employee->appointmentEmployees($options, true);
        $appointmentEmployees = AppointmentEmployeeResource::apiCollection($appointmentEmployees);

        return response()->json(['appointment_employees' => $appointmentEmployees]);
    }

    /**
     * Assign employee to appointment
     *
     * @param AssignEmployeeRequest $request
     * @return JsonResponse
     */
    public function assignEmployee(AssignEmployeeRequest $request): JsonResponse
    {
        $appointment = $request->getAppointment();
        $this->appointment->setModel($appointment);

        $user = $request->getEmployee();
        $this->appointment->assignEmployee($user);

        return apiResponse($this->appointment);
    }

    /**
     * Unassign employee from appointment
     *
     * @param UnassignEmployeeRequest $request
     * @return JsonResponse
     */
    public function unassignEmployee(UnassignEmployeeRequest $request): JsonResponse
    {
        $appointmentEmployee = $request->getAppointmentEmployee();
        $this->appointment->unassignEmployee($appointmentEmployee);

        return apiResponse($this->appointment);
    }
}
