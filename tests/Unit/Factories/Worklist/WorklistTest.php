<?php

namespace Tests\Unit\Factories\Worklist;

use App\Enums\Worklist\WorklistSortingRouteStatus;
use App\Enums\Worklist\WorklistStatus;
use App\Models\Worklist\Worklist;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WorklistTest extends TestCase
{
    use WithFaker;

    /**
     * Test create a company prepared worklist sorting route active instance
     *
     * @return void
     */
    public function test_create_company_prepared_worklist_with_sorting_route_active_instance()
    {
        // make an instance
        $worklist = Worklist::factory()->prepared()->sortingRouteActive()->create();

        // assert the instance
        $this->assertNotNull($worklist);
        $this->assertModelExists($worklist);
        $this->assertDatabaseHas('worklists', [
            'id' => $worklist->id,
            'company_id' => $worklist->company_id,
            'workday_id' => $worklist->workday_id,
            'user_id' => $worklist->user_id,
            'status' => WorklistStatus::Prepared,
            'sorting_route_status' => WorklistSortingRouteStatus::Active,
            'always_sorting_route_status' => WorklistSortingRouteStatus::Active,
            'worklist_name' => $worklist->worklist_name,
        ]);

        // assert the model relations
        $this->assertNotNull($worklist->company);
        $this->assertModelExists($worklist->company);
    }

    /**
     * Test create a company prepared worklist sorting route inactive instance
     *
     * @return void
     */
    public function test_create_company_prepared_worklist_with_sorting_route_inactive_instance()
    {
        // make an instance
        $worklist = Worklist::factory()->prepared()->sortingRouteInactive()->create();

        // assert the instance
        $this->assertNotNull($worklist);
        $this->assertModelExists($worklist);
        $this->assertDatabaseHas('worklists', [
            'id' => $worklist->id,
            'company_id' => $worklist->company_id,
            'workday_id' => $worklist->workday_id,
            'user_id' => $worklist->user_id,
            'status' => WorklistStatus::Prepared,
            'sorting_route_status' => WorklistSortingRouteStatus::Inactive,
            'always_sorting_route_status' => WorklistSortingRouteStatus::Inactive,
            'worklist_name' => $worklist->worklist_name,
        ]);

        // assert the model relations
        $this->assertNotNull($worklist->company);
        $this->assertModelExists($worklist->company);
    }

    /**
     * Test create a company processed worklist sorting route active instance
     *
     * @return void
     */
    public function test_create_company_processed_worklist_with_sorting_route_active_instance()
    {
        // make an instance
        $worklist = Worklist::factory()->processed()->sortingRouteActive()->create();

        // assert the instance
        $this->assertNotNull($worklist);
        $this->assertModelExists($worklist);
        $this->assertDatabaseHas('worklists', [
            'id' => $worklist->id,
            'company_id' => $worklist->company_id,
            'workday_id' => $worklist->workday_id,
            'user_id' => $worklist->user_id,
            'status' => WorklistStatus::Processed,
            'sorting_route_status' => WorklistSortingRouteStatus::Active,
            'always_sorting_route_status' => WorklistSortingRouteStatus::Active,
            'worklist_name' => $worklist->worklist_name,
        ]);

        // assert the model relations
        $this->assertNotNull($worklist->company);
        $this->assertModelExists($worklist->company);
    }

    /**
     * Test create a company processed worklist sorting route inactive instance
     *
     * @return void
     */
    public function test_create_company_processed_worklist_with_sorting_route_inactive_instance()
    {
        // make an instance
        $worklist = Worklist::factory()->processed()->sortingRouteInactive()->create();

        // assert the instance
        $this->assertNotNull($worklist);
        $this->assertModelExists($worklist);
        $this->assertDatabaseHas('worklists', [
            'id' => $worklist->id,
            'company_id' => $worklist->company_id,
            'workday_id' => $worklist->workday_id,
            'user_id' => $worklist->user_id,
            'status' => WorklistStatus::Processed,
            'sorting_route_status' => WorklistSortingRouteStatus::Inactive,
            'always_sorting_route_status' => WorklistSortingRouteStatus::Inactive,
            'worklist_name' => $worklist->worklist_name,
        ]);

        // assert the model relations
        $this->assertNotNull($worklist->company);
        $this->assertModelExists($worklist->company);
    }

    /**
     * Test create a company calculated worklist sorting route active instance
     *
     * @return void
     */
    public function test_create_company_calculated_worklist_with_sorting_route_active_instance()
    {
        // make an instance
        $worklist = Worklist::factory()->calculated()->sortingRouteActive()->create();

        // assert the instance
        $this->assertNotNull($worklist);
        $this->assertModelExists($worklist);
        $this->assertDatabaseHas('worklists', [
            'id' => $worklist->id,
            'company_id' => $worklist->company_id,
            'workday_id' => $worklist->workday_id,
            'user_id' => $worklist->user_id,
            'status' => WorklistStatus::Calculated,
            'sorting_route_status' => WorklistSortingRouteStatus::Active,
            'always_sorting_route_status' => WorklistSortingRouteStatus::Active,
            'worklist_name' => $worklist->worklist_name,
        ]);

        // assert the model relations
        $this->assertNotNull($worklist->company);
        $this->assertModelExists($worklist->company);
    }

    /**
     * Test create a company calculated worklist sorting route inactive instance
     *
     * @return void
     */
    public function test_create_company_calculated_worklist_with_sorting_route_inactive_instance()
    {
        // make an instance
        $worklist = Worklist::factory()->calculated()->sortingRouteInactive()->create();

        // assert the instance
        $this->assertNotNull($worklist);
        $this->assertModelExists($worklist);
        $this->assertDatabaseHas('worklists', [
            'id' => $worklist->id,
            'company_id' => $worklist->company_id,
            'workday_id' => $worklist->workday_id,
            'user_id' => $worklist->user_id,
            'status' => WorklistStatus::Calculated,
            'sorting_route_status' => WorklistSortingRouteStatus::Inactive,
            'always_sorting_route_status' => WorklistSortingRouteStatus::Inactive,
            'worklist_name' => $worklist->worklist_name,
        ]);

        // assert the model relations
        $this->assertNotNull($worklist->company);
        $this->assertModelExists($worklist->company);
    }

    /**
     * Test soft delete a company worklist instance
     *
     * @return void
     */
    public function test_soft_delete_company_worklist_instance()
    {
        // make an instance
        $worklist = Worklist::factory()->create();

        // assert the instance
        $this->assertNotNull($worklist);
        $this->assertModelExists($worklist);
        $this->assertDatabaseHas('worklists', [
            'id' => $worklist->id,
            'company_id' => $worklist->company_id,
        ]);

        // soft delete the instance
        $worklist->delete();

        // assert the soft deleted instance
        $this->assertSoftDeleted($worklist);
    }

    /**
     * Test hard delete a company worklist instance
     *
     * @return void
     */
    public function test_hard_delete_company_worklist_instance()
    {
        // make an instance
        $worklist = Worklist::factory()->create();

        // assert the instance
        $this->assertNotNull($worklist);
        $this->assertModelExists($worklist);
        $this->assertModelExists($worklist);
        $this->assertDatabaseHas('worklists', [
            'id' => $worklist->id,
            'company_id' => $worklist->company_id,
        ]);

        // hard delete the instance
        $worklistId = $worklist->id;
        $worklist->forceDelete();

        // assert the hard deleted instance
        $this->assertModelMissing($worklist);
        $this->assertDatabaseMissing('worklists', [
            'id' => $worklistId,
        ]);
    }

    /**
     * Test update worklist instance
     *
     * @return void
     */
    public function test_update_worklist_instance()
    {
        // make an instance
        $worklist = Worklist::factory()->create();

        // assert the instance
        $this->assertNotNull($worklist);
        $this->assertModelExists($worklist);
        $this->assertDatabaseHas('worklists', [
            'id' => $worklist->id,
            'company_id' => $worklist->company_id,
            'workday_id' => $worklist->workday_id,
            'worklist_name' => $worklist->worklist_name,
        ]);

        $worklistName = $this->faker->word;
        // update instance
        $worklist->update([
            'worklist_name' => $worklistName
        ]);

        $this->assertDatabaseHas('worklists', [
            'id' => $worklist->id,
            'company_id' => $worklist->company_id,
            'workday_id' => $worklist->workday_id,
            'worklist_name' => $worklistName,
        ]);
    }
}
