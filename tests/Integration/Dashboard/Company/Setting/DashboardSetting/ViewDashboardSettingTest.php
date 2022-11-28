<?php

namespace Tests\Integration\Dashboard\Company\Setting\DahsboardSetting;

use App\Models\User\User;
use Database\Factories\DashboardSettingFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 *  @see App\Http\Controllers\Api\Company\Setting\SettingController::view()
 *      to the tested controller method
 */
class ViewDashboardSettingTest extends TestCase
{
    use WithFaker;

    /**
    * Base API URL container constant.
    *
    * @const
    */
    public const BASE_MODULE_URL = '/api/dashboard/companies/settings/dashboard';

    /**
    * Authenticate the tester user to access the endpoint.
    *
    * @test
    * @return User
    */
    private function authenticate(): User
    {
        $user = User::factory()->owner()->create();
        $this->actingAs($user);

        return $user;
    }

     /**
     * Assert returned response has Setting resource instance.
     *
     * @test
     * @param TestResponse $response
     * @return void
     */
    private function assertResponseHasSettingResource(TestResponse $response): void
    {
        $content = $response->getOriginalContent();
        $this->assertArrayHasKey('setting', $content);

        $setting = $content['setting'];
        $this->assertArrayHasKey('id', $setting);
        $this->assertIsString($setting['id']);

        $this->assertArrayHasKey('result_graph', $setting);
        $this->assertIsNumeric($setting['result_graph']);

        $this->assertArrayHasKey('result_graph_description', $setting);
        $this->assertIsString($setting['result_graph_description']);

        $this->assertArrayHasKey('invoice_revenue_date_range', $setting);
        $this->assertIsNumeric($setting['invoice_revenue_date_range']);

        $this->assertArrayHasKey('best_selling_service_date_range', $setting);
        $this->assertIsNumeric($setting['best_selling_service_date_range']);
    }


    /**
    * Test view dashboard setting
    *
    * @return void
    */
    public function test_view_dashboard_setting(): void
    {
        $user = $this->authenticate();

        $company = $user->owner->company;

        $setting = DashboardSettingFactory::new()->create(['company_id' => $company->id]);

        $this->assertEquals($company->id, $setting->company->id);

        $response = $this->getJson(self::BASE_MODULE_URL);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) use ($user) {
            $json->has('setting');
            $json->where('setting.company_id', $user->owner->company_id);
        });
        $this->assertResponseHasSettingResource($response);
    }

    /**
    * Test view default dashboard setting
    *
    * @return void
    */
    public function test_view_defult_dashboard_setting(): void
    {
        $user = $this->authenticate();

        $response = $this->getJson(self::BASE_MODULE_URL);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) use ($user) {
            $json->has('setting');
            $json->whereType('setting.company_id', 'null');
        });
        $this->assertResponseHasSettingResource($response);
    }
}
