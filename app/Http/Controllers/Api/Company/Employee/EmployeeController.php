<?php

namespace App\Http\Controllers\Api\Company\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Employees\{DeleteEmployeeRequest as DeleteRequest,
    FindEmployeeRequest as FindRequest,
    PopulateEmployeesRequest as PopulateRequest,
    ResetEmployeePasswordRequest,
    RestoreEmployeeRequest as RestoreRequest,
    SaveEmployeeRequest as SaveRequest,
    SetImageRequest,
    UpdateEmployeeStatusRequest};
use App\Http\Resources\Employee\EmployeeResource;
use App\Repositories\Employee\EmployeeRepository;
use Illuminate\Http\JsonResponse;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @see \Tests\Feature\Dashboard\Company\Employee\EmployeeTest
 *      To the controller class unit tester class.
 */
class EmployeeController extends Controller
{
    /**
     * Employee Repository Class Container
     *
     * @var EmployeeRepository
     */
    private EmployeeRepository $employeeRepository;

    /**
     * Controller constructor method
     *
     * @param EmployeeRepository $employee
     * @return void
     */
    public function __construct(EmployeeRepository $employee)
    {
        $this->employeeRepository = $employee;
    }

    /**
     * Populate company employees
     *
     * @param PopulateRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Employee\EmployeeTest::test_populate_company_employees()
     *      To the controller method unit tester method.
     */
    public function companyEmployees(PopulateRequest $request): JsonResponse
    {
        $options = $request->options();
        $employees = $this->employeeRepository->all($options);
        $employees = $this->employeeRepository->paginate($options['per_page']);

        return response()->json([
            'employees' => EmployeeResource::apiCollection($employees),
        ]);
    }

    /**
     * Populate Company Invite-able employees
     * Employee that does not have controlling user
     *
     * @param PopulateRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Employee\EmployeeTest::test_populate_company_inviteable_employees()
     *      To the controller method unit tester method.
     */
    public function inviteableEmployees(PopulateRequest $request): JsonResponse
    {
        $options = $request->options();
        $employees = $this->employeeRepository->inviteables($options);
        $employees = $this->employeeRepository->paginate($options['per_page']);

        return response()->json([
            'employees' => EmployeeResource::apiCollection($employees),
        ]);
    }

    /**
     * Populate soft-deleted employees
     *
     * @param PopulateRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Employee\EmployeeTest::test_populate_company_trashed_employees()
     *      To the controller method unit tester method.
     */
    public function trashedEmployees(PopulateRequest $request): JsonResponse
    {
        $options = $request->options();
        $employees = $this->employeeRepository->trasheds($options);
        $employees = $this->employeeRepository->paginate($options['per_page']);

        return response()->json([
            'employees' => EmployeeResource::apiCollection($employees),
        ]);
    }

    /**
     * View employee with relationships related to it
     *
     * @param FindRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Employee\EmployeeTest::test_get_company_employee()
     *      To the controller method unit tester method.
     */
    public function view(FindRequest $request): JsonResponse
    {
        $employee = $request->getEmployee()
            ->load($request->getRelations());

        return response()->json([
            'employee' => new EmployeeResource($employee),
        ]);
    }

    /**
     * View employee document.
     *
     * @note Not used yet to any kinds of endpoint.
     *
     * @param FindRequest $request
     * @return JsonResponse
     */
    public function document(FindRequest $request): JsonResponse
    {
        $employee = $request->getEmployee()->load($request->getRelations());

        return response()->json([
            'document' => [
                'name' => 'Contract File',
                'document_name' => $employee->contract_file_path == null ? null : getFileNameFromPath($employee->contract_file_url),
                'document_url' => $employee->contract_file_path == null ? null : $employee->contract_file_url,
            ],
        ]);
    }

    /**
     * Store employee
     *
     * @param SaveRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Employee\EmployeeTest::test_store_company_employee()
     *      To the controller method unit tester method.
     */
    public function store(SaveRequest $request): JsonResponse
    {
        $employee = $this->employeeRepository->save($request->ruleWithCompany());

        return apiResponse($this->employeeRepository, [
            'employee' => new EmployeeResource($employee),
        ]);
    }

    /**
     * Update employee
     *
     * @param SaveRequest $request
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @see \Tests\Feature\Dashboard\Company\Employee\EmployeeTest::test_update_company_employee()
     *      To the controller method unit tester method.
     */
    public function update(SaveRequest $request): JsonResponse
    {
        $this->employeeRepository->setModel($request->getEmployee());
        $employee = $this->employeeRepository->save($request->ruleWithCompany());

        return apiResponse($this->employeeRepository, [
            'employee' => new EmployeeResource($employee->load(['user'])),
        ]);
    }

    /**
     * Update employee status.
     *
     * @param UpdateEmployeeStatusRequest $request
     * @return JsonResponse
     */
    public function updateStatus(UpdateEmployeeStatusRequest $request): JsonResponse
    {
        $this->employeeRepository->setModel($request->getEmployee());
        $employee = $this->employeeRepository->save([
            'employment_status' => $request->input('status'),
        ]);

        return apiResponse($this->employeeRepository, [
            'employee' => new EmployeeResource($employee),
        ]);
    }

    /**
     * Reset password for employee.
     *
     * @param ResetEmployeePasswordRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Employee\EmployeeTest::test_reset_employee_password
     *      To the controller method unit tester method.
     */
    public function resetPassword(ResetEmployeePasswordRequest $request): JsonResponse
    {
        $employee = $request->getEmployee();
        $this->employeeRepository->setModel($employee);

        $password = $request->input('password');
        $employee = $this->employeeRepository->resetPassword($password);

        return apiResponse($this->employeeRepository, [
            'employee' => new EmployeeResource($employee),
        ]);
    }

     /**
     * Toggle employment_status of employee by Active or Inactive
     *
     * @param FindRequest $request
     * @return Response
     * @see Tests\Feature\Dashboard\Company\Employee\EmployeeTest::test_toggle_employment_status_of_employee()
     *      to see the controller method's feature testing
     */
    public function toggle(FindRequest $request)
    {
        $employee = $this->employee->setModel($request->getEmployee());
        $data = [
            'employment_status' =>
                $employee->employment_status == EmploymentStatus::Active ?
                    EmploymentStatus::Active : EmploymentStatus::Inactive
         ];
        $employee = $this->employee->save($data);

        return apiResponse($this->employee, [
            'employee' => new EmployeeResource($employee),
        ]);
    }

    /**
     * Update employee image
     *
     * @param SetImageRequest $request
     * @return JsonResponse
     */
    public function setImage(SetImageRequest $request): JsonResponse
    {
        $this->employeeRepository->setModel($request->getEmployee());
        $employee = $this->employeeRepository->setImage($request->employee_image);

        return apiResponse($this->employeeRepository, [
            'employee' => new EmployeeResource($employee),
        ]);
    }

    /**
     * Delete employee
     *
     * @param DeleteRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Employee\EmployeeTest::test_delete_company_employee()
     * @see \Tests\Feature\Dashboard\Company\Employee\EmployeeTest::test_delete_company_employee_permanently()
     *      To the controller method unit tester methods.
     */
    public function delete(DeleteRequest $request): JsonResponse
    {
        $this->employeeRepository->setModel($request->getEmployee());
        $this->employeeRepository->delete(strtobool($request->input('force')));

        return apiResponse($this->employeeRepository);
    }

    /**
     * Restore employee
     *
     * @param RestoreRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Employee\EmployeeTest::test_restore_company_trashed_employee()
     *      To the controller method unit tester method.
     */
    public function restore(RestoreRequest $request): JsonResponse
    {
        $this->employeeRepository->setModel($request->getTrashedEmployee());
        $employee = $this->employeeRepository->restore();

        return apiResponse($this->employeeRepository, [
            'employee' => new EmployeeResource($employee),
        ]);
    }
}
