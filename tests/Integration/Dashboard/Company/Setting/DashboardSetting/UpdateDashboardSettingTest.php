<?php

namespace Tests\Integration\Dashboard\Company\Setting\DahsboardSetting;

use App\Enums\Setting\DashboardSetting\DashboardInvoiceRevenueDateRange;
use App\Enums\Setting\DashboardSetting\DashboardResultGraph;
use App\Models\User\User;
use Database\Factories\DashboardSettingFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 *  @see App\Http\Controllers\Api\Company\Setting\SettingController::update()
 *      to the tested controller
 */
class UpdateDashboardSettingTest extends TestCase
{
    use WithFaker;

    /**
    * Base API URL container constant.
    *
    * @const
    */
    public const BASE_MODULE_URL = '/api/dashboard/companies/settings/dashboard/update';

    /**
    * Authenticate the tester user to access the endpoint.
    *
    * @test
    * @return User
    */
    private function authenticate(): User
    {
        $user = User::factory()->owner()->create();
        $company = $user->owner->company;
        $this->assertEquals($user->owner->company_id, $company->id);

        // set as main owner
        $user->owner->is_prime_owner = true;
        $user->owner->save();
        $this->assertTrue($user->owner->fresh()->is_prime_owner);

        $this->actingAs($user);

        return $user;
    }

    /**
    * Test update dashboard setting
    *
    * @return void
    */
    public function test_update_dashboard_setting(): void
    {
        $user = $this->authenticate();
        $company = $user->owner->company;

        $this->actingAs($user);

        $setting = DashboardSettingFactory::new()->create([
            'company_id' => $company->id,
        ]);
        $this->assertEquals($company->id, $setting->company->id);

        $input = [
            'result_graph' => DashboardResultGraph::Weekly,
            'invoice_revenue_date_range' => DashboardInvoiceRevenueDateRange::Weekly,
            'best_selling_service_date_range' => 10,
        ];

        $response = $this->putJson(
            self::BASE_MODULE_URL,
            $input
        );

        // ensure that the value is updated
        $this->assertEquals(
            $input,
            $setting->fresh()->only([
                'result_graph' ,
                'invoice_revenue_date_range' ,
                 'best_selling_service_date_range'
                ])
        );

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) use ($user) {
            $json->has('setting');
            $json->has('status');
            $json->has('message');
        });
    }
}
