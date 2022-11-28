<?php

namespace Tests\Unit\Factories\Workday;

use App\Enums\Workday\WorkdayStatus;
use App\Jobs\Workday\GenerateMonthlyWorkdayJob;
use App\Jobs\Workday\GenerateWorkdayForPreviouslyInactiveCompanyJob;
use App\Models\Company\Company;
use App\Models\Workday\Workday;
use Illuminate\Foundation\Testing\WithFaker;
use Queue;
use Tests\TestCase;

class WorkdayTest extends TestCase
{
    use WithFaker;

    /**
     * Test create a company prepared workday instance
     *
     * @return void
     */
    public function test_create_company_prepared_workday_instance()
    {
        // make an instance
        $workday = Workday::factory()->prepared()->create();

        // assert the instance
        $this->assertNotNull($workday);
        $this->assertModelExists($workday);
        $this->assertDatabaseHas('workdays', [
            'id' => $workday->id,
            'company_id' => $workday->company_id,
            'date' => $workday->date,
            'status' => WorkdayStatus::Prepared
        ]);

        // assert the model relations
        $this->assertNotNull($workday->company);
        $this->assertModelExists($workday->company);
    }

    /**
     * Test create a company processed workday instance
     *
     * @return void
     */
    public function test_create_company_processed_workday_instance()
    {
        // make an instance
        $workday = Workday::factory()->processed()->create();

        // assert the instance
        $this->assertNotNull($workday);
        $this->assertModelExists($workday);
        $this->assertDatabaseHas('workdays', [
            'id' => $workday->id,
            'company_id' => $workday->company_id,
            'date' => $workday->date,
            'status' => WorkdayStatus::Processed
        ]);

        // assert the model relations
        $this->assertNotNull($workday->company);
        $this->assertModelExists($workday->company);
    }

    /**
     * Test create a company calculated workday instance
     *
     * @return void
     */
    public function test_create_company_calculated_workday_instance()
    {
        // make an instance
        $workday = Workday::factory()->calculated()->create();

        // assert the instance
        $this->assertNotNull($workday);
        $this->assertModelExists($workday);
        $this->assertDatabaseHas('workdays', [
            'id' => $workday->id,
            'company_id' => $workday->company_id,
            'date' => $workday->date,
            'status' => WorkdayStatus::Calculated
        ]);

        // assert the model relations
        $this->assertNotNull($workday->company);
        $this->assertModelExists($workday->company);
    }

    /**
     * Test create multiple company workday instances
     *
     * @return void
     */
    public function test_create_multiple_company_workday_instances()
    {
        // make the instances
        $count = 10;
        $workdays = Workday::factory($count)->create();

        // assert the instances
        $this->assertTrue(count($workdays) === $count);
    }

    /**
     * Test soft delete a company workday instance
     *
     * @return void
     */
    public function test_soft_delete_company_workday_instance()
    {
        // make an instance
        $workday = Workday::factory()->create();

        // assert the instance
        $this->assertNotNull($workday);
        $this->assertModelExists($workday);
        $this->assertDatabaseHas('workdays', [
            'id' => $workday->id,
            'date' => $workday->date,
            'company_id' => $workday->company_id,
        ]);

        // soft delete the instance
        $workday->delete();

        // assert the soft deleted instance
        $this->assertSoftDeleted($workday);
    }

    /**
     * Test hard delete a company workday instance
     *
     * @return void
     */
    public function test_hard_delete_company_workday_instance()
    {
        // make an instance
        $workday = Workday::factory()->create();

        // assert the instance
        $this->assertNotNull($workday);
        $this->assertModelExists($workday);
        $this->assertModelExists($workday);
        $this->assertDatabaseHas('workdays', [
            'id' => $workday->id,
            'date' => $workday->date,
            'company_id' => $workday->company_id,
        ]);

        // hard delete the instance
        $workdayId = $workday->id;
        $workday->forceDelete();

        // assert the hard deleted instance
        $this->assertModelMissing($workday);
        $this->assertDatabaseMissing('workdays', [
            'id' => $workdayId,
        ]);
    }

    /**
     * Test dispatch generate monthly workdays job
     *
     * @return void
     */
    public function test_dispatch_generate_monthly_workdays()
    {
        Queue::fake();
        GenerateMonthlyWorkdayJob::dispatch();
        Queue::assertPushed(GenerateMonthlyWorkdayJob::class);
    }

    /**
     * Test dispatch generate workdays job for inactive company
     *
     * @return void
     */
    public function test_dispatch_generate_workdays_job_for_inactive_company()
    {
        Queue::fake();
        $company = Company::factory()->create();
        GenerateWorkdayForPreviouslyInactiveCompanyJob::dispatch($company);
        Queue::assertPushed(GenerateWorkdayForPreviouslyInactiveCompanyJob::class);
    }
}
