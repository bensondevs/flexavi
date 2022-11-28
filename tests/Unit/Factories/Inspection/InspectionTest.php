<?php

namespace Tests\Unit\Factories\Inspection;

use App\Models\Appointment\Appointment;
use App\Models\Customer\Customer;
use App\Models\Inspection\Inspection;
use App\Models\User\User;
use Tests\TestCase;

class InspectionTest extends TestCase
{
    /**
     * Test create a company inspection instance
     *
     * @return void
     */
    public function test_create_company_inspection_instance()
    {
        // make an instance
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;

        $customer = Customer::factory()->for($company)->create();

        $appointment = Appointment::factory()->for($company)->for($customer)->createOneQuietly();

        $inspection = Inspection::factory()->for($company)->for($appointment)->create();

        $this->assertNotNull($inspection);
        $this->assertModelExists($inspection);
        $this->assertDatabaseHas('inspections', [
            'company_id' => $inspection->company_id,
            'appointment_id' => $inspection->appointment_id,
        ]);

        // assert the model relations
        $this->assertNotNull($inspection->company);
        $this->assertNotNull($inspection->appointment);
    }

    /**
     * Test create multiple company inspection instance
     *
     * @return void
     */
    public function test_create_multiple_company_inspection_instances()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;
        $customer = Customer::factory()->for($company)->create();

        $appointment = Appointment::factory()->for($company)->for($customer)->createOneQuietly();

        $count = 5;
        $inspection = Inspection::factory($count)->for($company)->for($appointment)->create();

        // asset the instance
        $this->assertTrue(count($inspection) === $count);
    }

    /**
     * Test soft delete a company inspection instance
     *
     * @return void
     */
    public function test_soft_delete_company_inspection_instance()
    {
        // make an instance
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;

        $customer = Customer::factory()->for($company)->create();

        $appointment = Appointment::factory()->for($company)->for($customer)->createOneQuietly();

        $inspection = Inspection::factory()->for($company)->for($appointment)->create();

        // assert the instance
        $this->assertNotNull($inspection);
        $this->assertModelExists($inspection);
        $this->assertDatabaseHas('inspections', [
            'company_id' => $inspection->company_id,
            'appointment_id' => $inspection->appointment_id,
        ]);

        // soft delete the instance
        $inspection->delete();

        // assert the soft deleted instance
        $this->assertSoftDeleted($inspection);
    }

    /**
     * Test hard delete a company inspection instance
     *
     * @return void
     */
    public function test_hard_delete_company_inspection_instance()
    {
        // make an instance
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;

        $customer = Customer::factory()->for($company)->create();

        $appointment = Appointment::factory()->for($company)->for($customer)->createOneQuietly();

        $inspection = Inspection::factory()->for($company)->for($appointment)->create();

        // assert the instance
        $this->assertNotNull($inspection);
        $this->assertModelExists($inspection);
        $this->assertDatabaseHas('inspections', [
            'company_id' => $inspection->company_id,
            'appointment_id' => $inspection->appointment_id,
        ]);

        // hart delete the instance
        $inspectionId = $inspection->id;
        $inspection->forceDelete();

        // assert the hard deleted instance
        $this->assertModelMissing($inspection);
        $this->assertDatabaseMissing('inspections', [
            'id' => $inspectionId
        ]);
    }

    /**
     * Test restore a trahsed company inspection instance
     *
     * @return void
     */

    public function test_restore_trashed_company_inspection_instance()
    {
        // make an instance
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;

        $customer = Customer::factory()->for($company)->create();

        $appointment = Appointment::factory()->for($company)->for($customer)->createOneQuietly();

        $inspection = Inspection::factory()->for($company)->for($appointment)->create();

        // assert the instance
        $this->assertNotNull($inspection);
        $this->assertModelExists($inspection);
        $this->assertDatabaseHas('inspections', [
            'company_id' => $inspection->company_id,
            'appointment_id' => $inspection->appointment_id,
        ]);

        // soft delete the instance
        $inspection->delete();

        // assert the soft deleted instance
        $this->assertSoftDeleted($inspection);

        // restore the trashed instance
        $inspection->restore();

        // assert the restored instance
        $this->assertNotSoftDeleted($inspection);

    }
}

