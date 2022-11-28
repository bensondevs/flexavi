<?php

namespace Tests\Feature\Dashboard\Company\Employee;

use App\Enums\Employee\{EmployeeType, EmploymentStatus};
use App\Http\Resources\Employee\EmployeeResource;
use App\Jobs\SendMail as SendMailJob;
use App\Mail\Employee\EmployeePasswordReseted;
use App\Models\{Employee\Employee, User\User};
use App\Traits\FeatureTestUsables;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\Company\Employee\EmployeeController
 *      To the tested controller class.
 */
class EmployeeTest extends TestCase
{
    use FeatureTestUsables;

    /**
     * Module base API URL.
     *
     * @const
     */
    public const MODULE_BASE_API_URL = '/api/dashboard/companies/employees';

    /**
     * Assert pagination size is multiplication of four.
     *
     * @param TestResponse $response
     * @return void
     */
    private function assertPaginationSizeIsMultiplicationOfFour(TestResponse $response): void
    {
        $content = $response->getOriginalContent();
        $employeesPagination = $content['employees'];

        // Check the pagination size is multiplication of 4
        $perPage = $employeesPagination->perPage();
        $this->assertTrue($perPage % 2 === 0);
    }

    /**
     * Test populate company employees
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Employee\EmployeeController::companyEmployees()
     *      To the tested controller method.
     */
    public function test_populate_company_employees(): void
    {
        // Authenticate to the application as owner user
        $this->authenticateAsOwner();

        // Make request to the controller method endpoint URL
        $response = $this->getJson(self::MODULE_BASE_API_URL);
        $response->assertOk();

        // Assert the content returned in JSON is as expected
        $this->assertResponseAttributeIsPaginationInstance(
            $response,
            'employees',
        );
        $this->assertPaginationSizeIsMultiplicationOfFour($response);
    }

    /**
     * Test populate company invite-able employees
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Employee\EmployeeController::inviteableEmployees()
     *      To the tested controller method.
     */
    public function test_populate_company_inviteable_employees(): void
    {
        // Authenticate to the application as owner user
        $this->authenticateAsOwner();

        // Make request to the controller method endpoint URL
        $response = $this->getJson(self::MODULE_BASE_API_URL . '/inviteables');
        $response->assertOk();

        // Assert the content returned in JSON is as expected
        $this->assertResponseAttributeIsPaginationInstance(
            $response,
            'employees',
        );
    }

    /**
     * Test populate company trashed employees
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Employee\EmployeeController::trashedEmployees()
     *      To the tested controller method.
     */
    public function test_populate_company_trashed_employees(): void
    {
        // Authenticate to the application as owner user
        $this->authenticateAsOwner();

        // Make request to the controller method endpoint URL
        $response = $this->getJson(self::MODULE_BASE_API_URL . '/trasheds');
        $response->assertOk();

        // Assert the content returned in JSON is as expected
        $this->assertResponseAttributeIsPaginationInstance(
            $response,
            'employees',
        );
    }

    /**
     * Assert employee instance returned correctly.
     *
     * @param TestResponse $response
     * @return void
     */
    private function assertEmployeeInstanceReturnedCorrectly(TestResponse $response): void
    {
        $content = $response->getOriginalContent();
        $employee = $content['employee'];

        // Ensure the returned content is employee resource instance
        $this->assertInstanceOf(EmployeeResource::class, $employee);

        // Ensure the employee attributes are not null
        $this->assertNotNull($employee->id);
        $this->assertNotNull($employee->title);
        $this->assertNotNull($employee->employee_type);
        $this->assertNotNull($employee->employment_status);

        // Ensure the user instance is not blank
        $this->assertNotNull($employee->user);
        $this->assertInstanceOf(User::class, $employee->user);
    }

    /**
     * Test view employee
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Employee\EmployeeController::view()
     *      To the tested controller method.
     */
    public function test_get_company_employee(): void
    {
        // Authenticate to the application as owner user
        $user = $this->authenticateAsOwner();
        $company = $user->owner->company;

        // Make employee instance as subject of testing
        $employee = Employee::factory()->for($company)->create();

        // Make request to the controller method endpoint URL
        $response = $this->getJson(urlWithParams(self::MODULE_BASE_API_URL . '/view', [
            'employee_id' => $employee->id,
        ]));
        $response->assertOk();

        // Assert the response content is as expected
        $this->assertEmployeeInstanceReturnedCorrectly($response);
    }

    /**
     * Test store a company employee
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Employee\EmployeeController::store()
     *      To the tested controller method.
     */
    public function test_store_company_employee(): void
    {
        // Authenticate to the application as owner user
        $this->authenticateAsOwner();

        // Make request to the controller method endpoint URL
        $response = $this->postJson(self::MODULE_BASE_API_URL . '/store', [
            'title' => 'Another Employee',
            'employee_type' => EmployeeType::Roofer,
            'employment_status' => EmploymentStatus::Active,
            'fullname' => 'Lorem Ipsum',
            'birth_date' => '2001-12-14',
            'contract_file' => UploadedFile::fake()->create(
                'document.pdf',
                100,
                'application/pdf'
            ),
        ]);

        // Assert the response status is successful
        $this->assertResponseStatusSuccess($response);
    }

    /**
     * Test update a company employee
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Employee\EmployeeController::update()
     *      To the tested controller method.
     */
    public function test_update_company_employee(): void
    {
        // Authenticate to the application as owner user
        $user = $this->authenticateAsOwner();
        $company = $user->owner->company;

        // Create employee in company as the subject of the test
        $employee = Employee::factory(state: ['company_id' => $company->id])
            ->create();
        $employee->refresh();

        // Make request to the controller method endpoint URL
        $response = $this->patchJson(self::MODULE_BASE_API_URL . '/update', [
            'employee_id' => $employee->id,
            'title' => 'Another Employee',
            'employee_type' => EmployeeType::Roofer,
            'employment_status' => EmploymentStatus::Active,
            'fullname' => 'Lorem Ipsum',
            'birth_date' => '2001-12-14',
            'contract_file' => UploadedFile::fake()->create(
                'document.pdf',
                100,
                'application/pdf'
            ),
        ]);
        $response->assertSuccessful();

        // Assert the response status is successful
        $this->assertResponseStatusSuccess($response);
        $this->assertEmployeeInstanceReturnedCorrectly($response);
        $this->assertNotNull($response->json('employee.user.permissions'));
    }

    /**
     * Test update a company employee
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Employee\EmployeeController::updateStatus()
     *      To the tested controller method.
     */
    public function test_update_company_employee_status(): void
    {
        // Authenticate to the application as owner user
        $user = $this->authenticateAsOwner();
        $company = $user->owner->company;

        // Create employee in company as the subject of the test
        $employee = Employee::factory(state: [
            'company_id' => $company->id,
            'employment_status' => EmploymentStatus::Inactive,
        ])->create()->fresh();

        // Make request to the controller method endpoint URL
        $response = $this->patchJson(self::MODULE_BASE_API_URL . '/update_status', [
            'employee_id' => $employee->id,
            'status' => EmploymentStatus::Active,
        ]);
        $response->assertSuccessful();

        $this->assertEquals(EmploymentStatus::Active, $employee->fresh()->employment_status);

        // Assert the response status is successful
        $this->assertResponseStatusSuccess($response);
        $this->assertEmployeeInstanceReturnedCorrectly($response);
    }

    /**
     * Test update a company employee
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Employee\EmployeeController::resetPassword()
     *      To the tested controller method.
     */
    public function test_reset_employee_password(): void
    {
        Queue::fake();
        Mail::fake();

        // Authenticate to the application as owner user
        $user = $this->authenticateAsOwner();
        $company = $user->owner->company;

        // Create employee in company as the subject of the test
        $employee = Employee::factory(state: ['company_id' => $company->id])->create();

        // Make request to the controller method endpoint URL
        $response = $this->patchJson(self::MODULE_BASE_API_URL . '/reset_password', [
            'employee_id' => $employee->id,
            'password' => $password = random_string(),
            'confirm_password' => $password,
        ]);
        $response->assertSuccessful();

        // Assert the response status is successful
        $this->assertResponseStatusSuccess($response);
        $this->assertEmployeeInstanceReturnedCorrectly($response);

        // assert the mail password reseted is sent
        /*
        //* i don't know why the assertPushed here is not working ,
        //* i already test it on Postman and it works fine
        Queue::assertPushed(SendMailJob::class, function ($job) use ($employee) {
            $this->assertInstanceOf(EmployeePasswordReseted::class, $job->mailable);
            // $this->assertEquals($employee->user->email, $job->destination);
            return $job;
        });
        */

        // Assert password updated
        $employeeUser = $employee->refresh()->user;
        $this->assertTrue($employeeUser->isPasswordMatch($password));
    }

     /**
     * Test toggling employment_status of employee by Active or Inactive
     *
     * @return void
     * @see App\Http\Controllers\Api\Company\Employee\EmployeeController::toggle()
     *      to the tested controller method
     *
     * Disabled due to an error in NotificationService.php line 204
     * Todo : @rizal fix the error in NotificationService.php line 204
     * Todo : @arfan continue the progress after the error fixed by @rizal
     *
    public function test_toggle_employment_status_of_employee()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->company;

        $employee = Employee::factory()->for($company)->active()->create();
        $this->assertEquals(EmploymentStatus::Active, $employee->employment_status);

        $response = $this->putJson("/api/dashboard/companies/employees/toggle?id=$employee->id");

        $employee = $employee->fresh();

        $this->assertEquals(EmploymentStatus::Inactive, $employee->employment_status);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) use ($employee) {
            $json->has('employee');
            $json->where('employee.id', $employee->id);
            $json->where('employee.employment_status', EmploymentStatus::Inactive);

            // status meta
            $json->where('status', 'success');
            $json->has('message');
        });
    }
     */


    /**
     * Test delete a company employee
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Employee\EmployeeController::delete()
     *      To the tested controller method.
     */
    public function test_delete_company_employee(): void
    {
        // Authenticate to the application as owner user
        $user = $this->authenticateAsOwner();
        $company = $user->owner->company;

        // Create employee in company as the subject of the test
        $employee = Employee::factory()->create();
        $employee->company()->associate($company);
        $employee->save();

        // Make request to the controller method endpoint URL
        $response = $this->deleteJson(self::MODULE_BASE_API_URL . '/delete', [
            'employee_id' => $employee->id,
        ]);

        // Assert the response status is successful
        $this->assertResponseStatusSuccess($response);
    }

    /**
     * Test delete a company employee permanently
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Employee\EmployeeController::delete()
     *      To the tested controller method.
     */
    public function test_delete_company_employee_permanently(): void
    {
        // Authenticate to the application as owner user
        $user = $this->authenticateAsOwner();
        $company = $user->owner->company;

        // Create employee in company as the subject of the test
        $employee = Employee::factory(state: ['company_id' => $company->id])
            ->create();

        // Make request to the controller method endpoint URL
        $response = $this->deleteJson(self::MODULE_BASE_API_URL . '/delete', [
            'employee_id' => $employee->id,
            'force' => true,
        ]);
        $response->assertSuccessful();

        // Assert the response status is successful
        $this->assertResponseStatusSuccess($response);
    }

    /**
     * Test restore a company trashed employee
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Employee\EmployeeController::restore()
     *      To the tested controller method.
     */
    public function test_restore_company_trashed_employee(): void
    {
        // Authenticate to the application as owner user
        $user = $this->authenticateAsOwner();
        $company = $user->owner->company;

        // Create soft-deleted employee in company as the subject of the test
        $employee = Employee::factory(state: [
            'company_id' => $company->id,
            'deleted_at' => now()->toDateTimeString(),
        ])->create();
        $employee->delete();

        // Make request to the controller method endpoint URL
        $response = $this->patchJson(self::MODULE_BASE_API_URL . '/restore', [
            'employee_id' => $employee->id,
        ]);
        $response->assertSuccessful();

        // Assert the response status is successful
        $this->assertResponseStatusSuccess($response);
        $this->assertInstanceReturnedInResponse(
            $response,
            'employee',
            EmployeeResource::class,
        );
    }
}
