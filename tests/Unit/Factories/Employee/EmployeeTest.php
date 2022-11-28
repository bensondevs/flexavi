<?php

namespace Tests\Unit\Factories\Employee;

use App\Enums\Employee\{EmployeeType, EmploymentStatus};
use App\Models\Employee\Employee;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use WithFaker;

    /**
     * Test create a company employee instance
     *
     * @return void
     */
    public function test_create_company_employee_instance()
    {
        // make an instance
        $employee = Employee::factory()->create();

        // assert the instance
        $this->assertNotNull($employee);
        $this->assertModelExists($employee);
        $this->assertDatabaseHas('employees', [
            'title' => $employee->title,
            'company_id' => $employee->company_id,
            'employee_type' => $employee->employee_type,
            'employment_status' => $employee->employment_status,
        ]);

        // assert the model relations
        $this->assertNotNull($employee->company);
        $this->assertModelExists($employee->company);
    }

    /**
     * Test create multiple company employee instances
     *
     * @return void
     */
    public function test_create_multiple_company_employee_instances()
    {
        // make the instances
        $count = 10;
        $employees = Employee::factory($count)->create();

        // assert the instances
        $this->assertTrue(count($employees) === $count);
    }

    /**
     * Test update a company employee instance
     *
     * @return void
     */
    public function test_update_company_employee_instance()
    {
        // make an instance
        $employee = Employee::factory()->create();

        // assert the instance
        $this->assertNotNull($employee);
        $this->assertModelExists($employee);
        $this->assertDatabaseHas('employees', [
            'title' => $employee->title,
            'company_id' => $employee->company_id,
            'employee_type' => $employee->employee_type,
            'employment_status' => $employee->employment_status,
        ]);

        // generate dummy data
        $title = Str::title($this->faker->word);
        $employeeType = $this->faker->randomElement([
            EmployeeType::Administrative,
            EmployeeType::Roofer,
        ]);
        $employmentStatus = $this->faker->randomElement([
            EmploymentStatus::Active,
            EmploymentStatus::Inactive,
        ]);

        // update instance
        $employee->update([
            'title' => $title,
            'employee_type' => $employeeType,
            'employment_status' => $employmentStatus,
        ]);

        // assert the updated instance
        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'company_id' => $employee->company_id,
            'title' => $title,
            'employee_type' => $employeeType,
            'employment_status' => $employmentStatus,
        ]);
    }

    /**
     * Test soft delete a company employee instance
     *
     * @return void
     */
    public function test_soft_delete_company_employee_instance()
    {
        // make an instance
        $employee = Employee::factory()->create();

        // assert the instance
        $this->assertNotNull($employee);
        $this->assertModelExists($employee);
        $this->assertDatabaseHas('employees', [
            'title' => $employee->title,
            'company_id' => $employee->company_id,
            'employee_type' => $employee->employee_type,
            'employment_status' => $employee->employment_status,
        ]);

        // soft delete the instance
        $employee->delete();

        // assert the soft deleted instance
        $this->assertSoftDeleted($employee);
    }

    /**
     * Test hard delete a company employee instance
     *
     * @return void
     */
    public function test_hard_delete_company_employee_instance()
    {
        // make an instance
        $employee = Employee::factory()->create();

        // assert the instance
        $this->assertNotNull($employee);
        $this->assertModelExists($employee);
        $this->assertDatabaseHas('employees', [
            'title' => $employee->title,
            'company_id' => $employee->company_id,
            'employee_type' => $employee->employee_type,
            'employment_status' => $employee->employment_status,
        ]);

        // hard delete the instance
        $employeeId = $employee->id;
        $employee->forceDelete();

        // assert the hard deleted instance
        $this->assertModelMissing($employee);
        $this->assertDatabaseMissing('employees', [
            'id' => $employeeId,
        ]);
    }

    /**
     * Test restore a trashed company employee instance
     *
     * @return void
     */
    public function test_restore_trashed_company_employee_instance()
    {
        // make an instance
        $employee = Employee::factory()->create();

        // assert the instance
        $this->assertNotNull($employee);
        $this->assertModelExists($employee);
        $this->assertDatabaseHas('employees', [
            'title' => $employee->title,
            'company_id' => $employee->company_id,
            'employee_type' => $employee->employee_type,
            'employment_status' => $employee->employment_status,
        ]);

        // soft delete the instance
        $employee->delete();

        // assert the soft deleted instance
        $this->assertSoftDeleted($employee);

        // restore the trashed instance
        $employee->restore();

        // assert the restored instance
        $this->assertNotSoftDeleted($employee);
    }
}
