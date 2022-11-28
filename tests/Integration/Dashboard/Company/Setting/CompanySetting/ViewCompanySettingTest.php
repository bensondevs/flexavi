<?php

namespace Tests\Integration\Dashboard\Company\Setting\CompanySetting;

use App\Enums\Setting\SettingModule;
use App\Models\User\User;
use Database\Factories\CompanySettingFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 *  @see App\Http\Controllers\Api\Company\Setting\SettingController::view()
 *      to the tested controller method
 */
class ViewCompanySettingTest extends TestCase
{
    use WithFaker;

    /**
    * Base API URL container constant.
    *
    * @const
    */
    public const BASE_MODULE_URL = '/api/dashboard/companies/settings/company';

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

        $this->assertArrayHasKey('auto_subs_same_plan_while_ends', $setting);
        $this->assertIsBool($setting['auto_subs_same_plan_while_ends']);

        $this->assertArrayHasKey('invoicing_address_same_as_visiting_address', $setting);
        $this->assertIsBool($setting['invoicing_address_same_as_visiting_address']);
    }

    /**
    * Test view company setting
    *
    * @return void
    */
    public function test_view_company_setting(): void
    {
        $user = $this->authenticate();

        $company = $user->owner->company;

        $module = SettingModule::Company;
        $setting = CompanySettingFactory::new()->create(['company_id' => $company->id]);

        $this->assertEquals($company->id, $setting->company->id);

        $response = $this->getJson(self::BASE_MODULE_URL . "?module=$module");

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) use ($user) {
            $json->has('setting');
            $json->where('setting.company_id', $user->owner->company_id);
        });
        $this->assertResponseHasSettingResource($response);
    }

    /**
    * Test view default company setting
    *
    * @return void
    */
    public function test_view_defult_company_setting(): void
    {
        $user = $this->authenticate();

        $module = SettingModule::Company;

        $response = $this->getJson(self::BASE_MODULE_URL . "?module=$module");

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) use ($user) {
            $json->has('setting');
            $json->whereType('setting.company_id', 'null');
        });
        $this->assertResponseHasSettingResource($response);
    }
}
