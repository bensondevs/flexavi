<?php

namespace Tests\Unit\Factories\Fleet;

use App\Models\Car\CarRegisterTime;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CarRegisterTimeTest extends TestCase
{
    use WithFaker;

    /**
     * Test create a company car register time instance
     *
     * @return void
     */
    public function test_create_company_car_register_time_instance()
    {
        // make an instance
        $carRegisterTime = CarRegisterTime::factory()->create();

        // assert the instance
        $this->assertNotNull($carRegisterTime);
        $this->assertModelExists($carRegisterTime);
        $this->assertDatabaseHas('car_register_times', [
            'id' => $carRegisterTime->id,
            'company_id' => $carRegisterTime->company_id,
            'worklist_id' => $carRegisterTime->worklist_id,
            'car_id' => $carRegisterTime->car_id,
            'should_out_at' => $carRegisterTime->should_out_at,
            'should_return_at' => $carRegisterTime->should_return_at,
        ]);

        // assert the instance relations
        $this->assertNotNull($carRegisterTime->company);
        $this->assertModelExists($carRegisterTime->company);
        $this->assertNotNull($carRegisterTime->car);
        $this->assertModelExists($carRegisterTime->car);
    }

    /**
     * Test create a company car register worklist instance
     *
     * @return void
     */
    public function test_create_company_car_register_worklist_instance()
    {
        // make an instance
        $carRegisterWorklist = CarRegisterTime::factory()
            ->assignedToWorklist()
            ->create();

        // assert the instance
        $this->assertNotNull($carRegisterWorklist);
        $this->assertModelExists($carRegisterWorklist);
        $this->assertDatabaseHas('car_register_times', [
            'id' => $carRegisterWorklist->id,
            'company_id' => $carRegisterWorklist->company_id,
            'worklist_id' => $carRegisterWorklist->worklist_id,
            'car_id' => $carRegisterWorklist->car_id,
            'should_out_at' => $carRegisterWorklist->should_out_at,
            'should_return_at' => $carRegisterWorklist->should_return_at,
        ]);

        // assert the instance relations
        $this->assertNotNull($carRegisterWorklist->company);
        $this->assertModelExists($carRegisterWorklist->company);
        $this->assertNotNull($carRegisterWorklist->car);
        $this->assertModelExists($carRegisterWorklist->car);
        $this->assertNotNull($carRegisterWorklist->worklist);
        $this->assertModelExists($carRegisterWorklist->worklist);
    }

    /**
     * Test create multiple company car register time instances
     *
     * @return void
     */
    public function test_create_multiple_company_car_register_time_instances()
    {
        // make the instances
        $count = 10;
        $carRegisterTimes = CarRegisterTime::factory($count)->create();

        // assert the instances
        $this->assertTrue(count($carRegisterTimes) === $count);
    }

    /**
     * Test create multiple company car register worklist instances
     *
     * @return void
     */
    public function test_create_multiple_company_car_register_worklist_instances()
    {
        // make the instances
        $count = 10;
        $carRegisterWorklists = CarRegisterTime::factory($count)
            ->assignedToWorklist()
            ->create();

        // assert the instances
        $this->assertTrue(count($carRegisterWorklists) === $count);
    }

    /**
     * Test update a company car register time instance
     *
     * @return void
     */
    public function test_update_company_car_register_time_instance()
    {
        // make an instance
        $carRegisterTime = CarRegisterTime::factory()->create();

        // assert the instance
        $this->assertNotNull($carRegisterTime);
        $this->assertModelExists($carRegisterTime);
        $this->assertDatabaseHas('car_register_times', [
            'id' => $carRegisterTime->id,
            'company_id' => $carRegisterTime->company_id,
            'worklist_id' => $carRegisterTime->worklist_id,
            'car_id' => $carRegisterTime->car_id,
            'should_out_at' => $carRegisterTime->should_out_at,
            'should_return_at' => $carRegisterTime->should_return_at,
        ]);

        // generate dummy data
        $shouldOutAt = Carbon::now()->addDays(5);
        $shouldReturnAt = $shouldOutAt->addDays(10);

        // update instance
        $carRegisterTime->update([
            'should_out_at' => $shouldOutAt->format($this->preferedDateFormat),
            'should_return_at' => $shouldReturnAt->format(
                $this->preferedDateFormat
            ),
        ]);

        // assert the updated instance
        $this->assertDatabaseHas('car_register_times', [
            'id' => $carRegisterTime->id,
            'company_id' => $carRegisterTime->company_id,
            'worklist_id' => $carRegisterTime->worklist_id,
            'car_id' => $carRegisterTime->car_id,
            'should_out_at' => $shouldOutAt->format($this->preferedDateFormat),
            'should_return_at' => $shouldReturnAt->format(
                $this->preferedDateFormat
            ),
        ]);
    }

    /**
     * Test update a company car register worklist instance
     *
     * @return void
     */
    public function test_update_company_car_register_worklist_instance()
    {
        // make an instance
        $carRegisterWorklist = CarRegisterTime::factory()
            ->assignedToWorklist()
            ->create();

        // assert the instance
        $this->assertNotNull($carRegisterWorklist);
        $this->assertModelExists($carRegisterWorklist);
        $this->assertDatabaseHas('car_register_times', [
            'id' => $carRegisterWorklist->id,
            'company_id' => $carRegisterWorklist->company_id,
            'worklist_id' => $carRegisterWorklist->worklist_id,
            'car_id' => $carRegisterWorklist->car_id,
            'should_out_at' => $carRegisterWorklist->should_out_at,
            'should_return_at' => $carRegisterWorklist->should_return_at,
        ]);

        // generate dummy data
        $shouldOutAt = Carbon::now()->addDays(5);
        $shouldReturnAt = $shouldOutAt->addDays(10);

        // update instance
        $carRegisterWorklist->update([
            'should_out_at' => $shouldOutAt->format($this->preferedDateFormat),
            'should_return_at' => $shouldReturnAt->format(
                $this->preferedDateFormat
            ),
        ]);

        // assert the updated instance
        $this->assertDatabaseHas('car_register_times', [
            'id' => $carRegisterWorklist->id,
            'company_id' => $carRegisterWorklist->company_id,
            'worklist_id' => $carRegisterWorklist->worklist_id,
            'car_id' => $carRegisterWorklist->car_id,
            'should_out_at' => $shouldOutAt->format($this->preferedDateFormat),
            'should_return_at' => $shouldReturnAt->format(
                $this->preferedDateFormat
            ),
        ]);
    }

    /**
     * Test soft delete a company car register time instance
     *
     * @return void
     */
    public function test_soft_delete_company_car_register_time_instance()
    {
        // make an instance
        $carRegisterTime = CarRegisterTime::factory()->create();

        // assert the instance
        $this->assertNotNull($carRegisterTime);
        $this->assertModelExists($carRegisterTime);
        $this->assertDatabaseHas('car_register_times', [
            'id' => $carRegisterTime->id,
            'company_id' => $carRegisterTime->company_id,
            'worklist_id' => $carRegisterTime->worklist_id,
            'car_id' => $carRegisterTime->car_id,
            'should_out_at' => $carRegisterTime->should_out_at,
            'should_return_at' => $carRegisterTime->should_return_at,
        ]);

        // soft delete the instance
        $carRegisterTime->delete();

        // assert the soft deleted instance
        $this->assertSoftDeleted($carRegisterTime);
    }

    /**
     * Test soft delete a company car register worklist instance
     *
     * @return void
     */
    public function test_soft_delete_company_car_register_worklist_instance()
    {
        // make an instance
        $carRegisterWorklist = CarRegisterTime::factory()
            ->assignedToWorklist()
            ->create();

        // assert the instance
        $this->assertNotNull($carRegisterWorklist);
        $this->assertModelExists($carRegisterWorklist);
        $this->assertDatabaseHas('car_register_times', [
            'id' => $carRegisterWorklist->id,
            'company_id' => $carRegisterWorklist->company_id,
            'worklist_id' => $carRegisterWorklist->worklist_id,
            'car_id' => $carRegisterWorklist->car_id,
            'should_out_at' => $carRegisterWorklist->should_out_at,
            'should_return_at' => $carRegisterWorklist->should_return_at,
        ]);

        // soft delete the instance
        $carRegisterWorklist->delete();

        // assert the soft deleted instance
        $this->assertSoftDeleted($carRegisterWorklist);
    }

    /**
     * Test hard delete a company car register time instance
     *
     * @return void
     */
    public function test_hard_delete_company_car_register_time_instance()
    {
        // make an instance
        $carRegisterTime = CarRegisterTime::factory()->create();

        // assert the instance
        $this->assertNotNull($carRegisterTime);
        $this->assertModelExists($carRegisterTime);
        $this->assertDatabaseHas('car_register_times', [
            'id' => $carRegisterTime->id,
            'company_id' => $carRegisterTime->company_id,
            'worklist_id' => $carRegisterTime->worklist_id,
            'car_id' => $carRegisterTime->car_id,
            'should_out_at' => $carRegisterTime->should_out_at,
            'should_return_at' => $carRegisterTime->should_return_at,
        ]);

        // hard delete the instance
        $carRegisterTimeId = $carRegisterTime->id;
        $carRegisterTime->forceDelete();

        // assert the hard deleted instance
        $this->assertModelMissing($carRegisterTime);
        $this->assertDatabaseMissing('car_register_times', [
            'id' => $carRegisterTimeId,
        ]);
    }

    /**
     * Test hard delete a company car register worklist instance
     *
     * @return void
     */
    public function test_hard_delete_company_car_register_worklist_instance()
    {
        // make an instance
        $carRegisterWorklist = CarRegisterTime::factory()
            ->assignedToWorklist()
            ->create();

        // assert the instance
        $this->assertNotNull($carRegisterWorklist);
        $this->assertModelExists($carRegisterWorklist);
        $this->assertDatabaseHas('car_register_times', [
            'id' => $carRegisterWorklist->id,
            'company_id' => $carRegisterWorklist->company_id,
            'worklist_id' => $carRegisterWorklist->worklist_id,
            'car_id' => $carRegisterWorklist->car_id,
            'should_out_at' => $carRegisterWorklist->should_out_at,
            'should_return_at' => $carRegisterWorklist->should_return_at,
        ]);

        // hard delete the instance
        $carRegisterWorklistId = $carRegisterWorklist->id;
        $carRegisterWorklist->forceDelete();

        // assert the hard deleted instance
        $this->assertModelMissing($carRegisterWorklist);
        $this->assertDatabaseMissing('car_register_times', [
            'id' => $carRegisterWorklistId,
        ]);
    }

    /**
     * Test restore a trashed company car register time instance
     *
     * @return void
     */
    public function test_restore_trashed_company_car_register_time_instance()
    {
        // make an instance
        $carRegisterTime = CarRegisterTime::factory()->create();

        // assert the instance
        $this->assertNotNull($carRegisterTime);
        $this->assertModelExists($carRegisterTime);
        $this->assertDatabaseHas('car_register_times', [
            'id' => $carRegisterTime->id,
            'company_id' => $carRegisterTime->company_id,
            'worklist_id' => $carRegisterTime->worklist_id,
            'car_id' => $carRegisterTime->car_id,
            'should_out_at' => $carRegisterTime->should_out_at,
            'should_return_at' => $carRegisterTime->should_return_at,
        ]);

        // soft delete the instance
        $carRegisterTime->delete();

        // assert the soft deleted instance
        $this->assertSoftDeleted($carRegisterTime);

        // restore the trashed instance
        $carRegisterTime->restore();

        // assert the restored instance
        $this->assertNotSoftDeleted($carRegisterTime);
    }

    /**
     * Test restore a trashed company car register worklist instance
     *
     * @return void
     */
    public function test_restore_trashed_company_car_register_worklist_instance()
    {
        // make an instance
        $carRegisterWorklist = CarRegisterTime::factory()
            ->assignedToWorklist()
            ->create();

        // assert the instance
        $this->assertNotNull($carRegisterWorklist);
        $this->assertModelExists($carRegisterWorklist);
        $this->assertDatabaseHas('car_register_times', [
            'id' => $carRegisterWorklist->id,
            'company_id' => $carRegisterWorklist->company_id,
            'worklist_id' => $carRegisterWorklist->worklist_id,
            'car_id' => $carRegisterWorklist->car_id,
            'should_out_at' => $carRegisterWorklist->should_out_at,
            'should_return_at' => $carRegisterWorklist->should_return_at,
        ]);

        // soft delete the instance
        $carRegisterWorklist->delete();

        // assert the soft deleted instance
        $this->assertSoftDeleted($carRegisterWorklist);

        // restore the trashed instance
        $carRegisterWorklist->restore();

        // assert the restored instance
        $this->assertNotSoftDeleted($carRegisterWorklist);
    }
}
