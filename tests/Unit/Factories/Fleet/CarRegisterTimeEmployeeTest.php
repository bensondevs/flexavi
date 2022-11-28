<?php

namespace Tests\Unit\Factories\Fleet;

use App\Enums\CarRegisterTimeEmployee\PassangerType;
use App\Models\{Car\CarRegisterTimeEmployee, Employee\Employee};
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CarRegisterTimeEmployeeTest extends TestCase
{
    use WithFaker;

    /**
     * Test create a company car register time employee driver instance
     *
     * @return void
     */
    public function test_create_company_car_register_time_employee_driver_instance()
    {
        // make an instance
        $carRegisterTimeEmployee = CarRegisterTimeEmployee::factory()
            ->driver()
            ->create();

        // assert the instance
        $this->assertNotNull($carRegisterTimeEmployee);
        $this->assertModelExists($carRegisterTimeEmployee);
        $this->assertDatabaseHas('car_register_time_employees', [
            'id' => $carRegisterTimeEmployee->id,
            'company_id' => $carRegisterTimeEmployee->company_id,
            'car_register_time_id' =>
                $carRegisterTimeEmployee->car_register_time_id,
            'employee_id' => $carRegisterTimeEmployee->employee_id,
            'passanger_type' => $carRegisterTimeEmployee->passanger_type,
        ]);
        $this->assertTrue(
            $carRegisterTimeEmployee->passanger_type === PassangerType::Driver
        );

        // assert the model relations
        $this->assertNotNull($carRegisterTimeEmployee->company);
        $this->assertModelExists($carRegisterTimeEmployee->company);
        $this->assertNotNull($carRegisterTimeEmployee->carRegisterTime);
        $this->assertModelExists($carRegisterTimeEmployee->carRegisterTime);
        $this->assertNotNull($carRegisterTimeEmployee->employee);
        $this->assertModelExists($carRegisterTimeEmployee->employee);
    }

    /**
     * Test create a company car register time employee passanger instance
     *
     * @return void
     */
    public function test_create_company_car_register_time_employee_passanger_instance()
    {
        // make an instance
        $carRegisterTimeEmployee = CarRegisterTimeEmployee::factory()
            ->passanger()
            ->create();

        // assert the instance
        $this->assertNotNull($carRegisterTimeEmployee);
        $this->assertModelExists($carRegisterTimeEmployee);
        $this->assertDatabaseHas('car_register_time_employees', [
            'id' => $carRegisterTimeEmployee->id,
            'company_id' => $carRegisterTimeEmployee->company_id,
            'car_register_time_id' =>
                $carRegisterTimeEmployee->car_register_time_id,
            'employee_id' => $carRegisterTimeEmployee->employee_id,
            'passanger_type' => $carRegisterTimeEmployee->passanger_type,
        ]);
        $this->assertTrue(
            $carRegisterTimeEmployee->passanger_type ===
            PassangerType::Passanger
        );

        // assert the model relations
        $this->assertNotNull($carRegisterTimeEmployee->company);
        $this->assertModelExists($carRegisterTimeEmployee->company);
        $this->assertNotNull($carRegisterTimeEmployee->carRegisterTime);
        $this->assertModelExists($carRegisterTimeEmployee->carRegisterTime);
        $this->assertNotNull($carRegisterTimeEmployee->employee);
        $this->assertModelExists($carRegisterTimeEmployee->employee);
    }

    /**
     * Test create multiple company car register time employee passanger instances
     *
     * @return void
     */
    public function test_create_multiple_company_car_register_time_employee_passanger_instances()
    {
        // make the instances
        $count = 10;
        $assignedEmployees = CarRegisterTimeEmployee::factory($count)
            ->passanger()
            ->create();

        // assert the instances
        $this->assertTrue(count($assignedEmployees) === $count);
    }

    /**
     * Test add an employee relation to company car register time
     *
     * @return void
     */
    public function test_add_employee_relation_to_company_car_register_time()
    {
        // make the instances
        $count = 10;
        $employee = Employee::factory()->create();
        $carRegisterTimeEmployee = CarRegisterTimeEmployee::factory()
            ->driver()
            ->create();

        // assert the instances
        $this->assertNotNull($employee);
        $this->assertModelExists($employee);
        $this->assertNotNull($carRegisterTimeEmployee);
        $this->assertModelExists($carRegisterTimeEmployee);

        // assign the employee
        $carRegisterTimeEmployee->employee = $employee;
        $carRegisterTimeEmployee->save();
        $carRegisterTimeEmployee = $carRegisterTimeEmployee->fresh();

        // assert the model relations
        $this->assertNotNull($carRegisterTimeEmployee->company);
        $this->assertModelExists($carRegisterTimeEmployee->company);
        $this->assertNotNull($carRegisterTimeEmployee->carRegisterTime);
        $this->assertModelExists($carRegisterTimeEmployee->carRegisterTime);
        $this->assertNotNull($carRegisterTimeEmployee->employee);
        $this->assertModelExists($carRegisterTimeEmployee->employee);
        $this->assertTrue(
            $carRegisterTimeEmployee->employee->id === $employee->id
        );
    }

    /**
     * Test update a company car register time employee instance
     *
     * @return void
     */
    public function test_update_company_car_register_time_employee_instance()
    {
        // make an instance
        $carRegisterTimeEmployee = CarRegisterTimeEmployee::factory()
            ->driver()
            ->create();

        // assert the instance
        $this->assertNotNull($carRegisterTimeEmployee);
        $this->assertModelExists($carRegisterTimeEmployee);
        $this->assertDatabaseHas('car_register_time_employees', [
            'id' => $carRegisterTimeEmployee->id,
            'company_id' => $carRegisterTimeEmployee->company_id,
            'car_register_time_id' =>
                $carRegisterTimeEmployee->car_register_time_id,
            'employee_id' => $carRegisterTimeEmployee->employee_id,
            'passanger_type' => $carRegisterTimeEmployee->passanger_type,
        ]);

        // generate dummy data
        $passangerType = rand(PassangerType::Driver, PassangerType::Passanger);

        // update instance
        $carRegisterTimeEmployee->update([
            'passanger_type' => $passangerType,
        ]);

        // assert the updated instance
        $this->assertDatabaseHas('car_register_time_employees', [
            'id' => $carRegisterTimeEmployee->id,
            'company_id' => $carRegisterTimeEmployee->company_id,
            'car_register_time_id' =>
                $carRegisterTimeEmployee->car_register_time_id,
            'employee_id' => $carRegisterTimeEmployee->employee_id,
            'passanger_type' => $passangerType,
        ]);
    }

    /**
     * Test soft delete a company car register time employee instance
     *
     * @return void
     */
    public function test_soft_delete_company_car_register_time_employee_instance()
    {
        // make an instance
        $carRegisterTimeEmployee = CarRegisterTimeEmployee::factory()
            ->driver()
            ->create();

        // assert the instance
        $this->assertNotNull($carRegisterTimeEmployee);
        $this->assertModelExists($carRegisterTimeEmployee);
        $this->assertDatabaseHas('car_register_time_employees', [
            'id' => $carRegisterTimeEmployee->id,
            'company_id' => $carRegisterTimeEmployee->company_id,
            'car_register_time_id' =>
                $carRegisterTimeEmployee->car_register_time_id,
            'employee_id' => $carRegisterTimeEmployee->employee_id,
            'passanger_type' => $carRegisterTimeEmployee->passanger_type,
        ]);

        // soft delete the instance
        $carRegisterTimeEmployee->delete();

        // assert the soft deleted instance
        $this->assertSoftDeleted($carRegisterTimeEmployee);
    }

    /**
     * Test hard delete a company car register time employee instance
     *
     * @return void
     */
    public function test_hard_delete_company_car_register_time_employee_instance()
    {
        // make an instance
        $carRegisterTimeEmployee = CarRegisterTimeEmployee::factory()
            ->driver()
            ->create();

        // assert the instance
        $this->assertNotNull($carRegisterTimeEmployee);
        $this->assertModelExists($carRegisterTimeEmployee);
        $this->assertDatabaseHas('car_register_time_employees', [
            'id' => $carRegisterTimeEmployee->id,
            'company_id' => $carRegisterTimeEmployee->company_id,
            'car_register_time_id' =>
                $carRegisterTimeEmployee->car_register_time_id,
            'employee_id' => $carRegisterTimeEmployee->employee_id,
            'passanger_type' => $carRegisterTimeEmployee->passanger_type,
        ]);

        // hard delete the instance
        $carRegisterTimeEmployeeId = $carRegisterTimeEmployee->id;
        $carRegisterTimeEmployee->forceDelete();

        // assert the hard deleted instance
        $this->assertModelMissing($carRegisterTimeEmployee);
        $this->assertDatabaseMissing('car_register_time_employees', [
            'id' => $carRegisterTimeEmployeeId,
        ]);
    }

    /**
     * Test restore a trashed company car register time employee instance
     *
     * @return void
     */
    public function test_restore_trashed_company_car_register_time_employee_instance()
    {
        // make an instance
        $carRegisterTimeEmployee = CarRegisterTimeEmployee::factory()
            ->driver()
            ->create();

        // assert the instance
        $this->assertNotNull($carRegisterTimeEmployee);
        $this->assertModelExists($carRegisterTimeEmployee);
        $this->assertDatabaseHas('car_register_time_employees', [
            'id' => $carRegisterTimeEmployee->id,
            'company_id' => $carRegisterTimeEmployee->company_id,
            'car_register_time_id' =>
                $carRegisterTimeEmployee->car_register_time_id,
            'employee_id' => $carRegisterTimeEmployee->employee_id,
            'passanger_type' => $carRegisterTimeEmployee->passanger_type,
        ]);

        // soft delete the instance
        $carRegisterTimeEmployee->delete();

        // assert the soft deleted instance
        $this->assertSoftDeleted($carRegisterTimeEmployee);

        // restore the trashed instance
        $carRegisterTimeEmployee->restore();

        // assert the restored instance
        $this->assertNotSoftDeleted($carRegisterTimeEmployee);
    }
}
