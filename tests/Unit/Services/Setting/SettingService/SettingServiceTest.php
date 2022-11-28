<?php

namespace Tests\Unit\Services\Setting\SettingService;

use App\Enums\Setting\DashboardSetting\DashboardResultGraph;
use App\Enums\Setting\SettingModule;
use App\Models\Setting\DashboardSetting;
use Database\Factories\CompanyFactory;
use Database\Factories\DashboardSettingFactory;
use Illuminate\Foundation\Testing\WithFaker;
use App\Services\Setting\SettingService;
use Tests\TestCase;

/**
 * @see \App\Services\Setting\SettingService
 *      To the tested service class.
 * @see https://app.clickup.com/t/357ad06
 *      To view tickets when they were created
 */
class SettingServiceTest extends TestCase
{
    use WithFaker;

    /**
     * Service instance container property.
     *
     * @var ?SettingService
     */
    private ?SettingService $service = null;

    /**
     * Test basic for making sure the class is not crashing.
     *
     * @test
     * @return void
     */
    public function it_does_not_have_stupid_error(): void
    {
        $this->assertInstanceOf(
            SettingService::class,
            $this->settingService()
        );
    }

    /**
     * Create or get service instance
     *
     * @param bool $force
     * @return SettingService
     */
    protected function settingService(bool $force = false): SettingService
    {
        if ($this->service instanceof SettingService and !$force) {
            return $this->service;
        }

        return $this->service = new SettingService();
    }

     /**
    * It method should find and return setting
    *
    * @test
    * @return void
    */
    public function it_method_should_find_and_return_setting(): void
    {
        $company = CompanyFactory::new()->create();
        $createdSetting = DashboardSettingFactory::new()->for($company)->create();
        $this->assertDatabaseHas((new DashboardSetting())->getTable(), ['company_id' => $company->id]);

        $returnedSetting = SettingService::find($company, SettingModule::Dashboard);

        $this->assertEquals($createdSetting->id, $returnedSetting->id);
    }

    /**
     * It method should create a new setting
     *
     * @test
     * @return void
     */
    public function it_method_should_create_a_new_setting(): void
    {
        $company = CompanyFactory::new()->create();
        $this->assertDatabaseMissing((new DashboardSetting())->getTable(), ['company_id' => $company->id]);

        $data = [
            'company_id' => $company->id,
            'result_graph' => DashboardResultGraph::getRandomValue(),
            'invoice_revenue_date_range' => rand(1, 10),
            'best_selling_service_date_range' => rand(1, 10),
        ];
        $setting = SettingService::updateOrCreate($data, SettingModule::Dashboard);

        $this->assertDatabaseHas((new DashboardSetting())->getTable(), ['company_id' => $company->id]);
        $this->assertEquals($company->id, $setting->company_id);
    }
}
