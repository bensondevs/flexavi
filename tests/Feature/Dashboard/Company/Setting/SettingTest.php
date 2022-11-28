<?php

namespace Tests\Feature\Dashboard\Company\Setting;

use App\Enums\Setting\SettingModule;
use App\Models\User\User;
use Database\Factories\QuotationSettingFactory;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use WithFaker;

    /**
     * Base API URL container constant.
     *
     * @const
     */
    public const BASE_MODULE_URL = '/api/dashboard/companies/settings';

    /**
     * Test populate settings
     *
     * @return void
     */
    public function test_view_setting()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;

        $module = SettingModule::Quotation;
        $setting = QuotationSettingFactory::new()->create(['company_id' => $company->id]);

        $this->assertEquals($company->id, $setting->company->id);

        $response = $this->getJson(self::BASE_MODULE_URL . "/view?module=$module");

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) use ($user) {
            $json->has('setting');
            $json->where('setting.company_id', $user->owner->company_id);
        });
    }

    /**
     * Test update existing setting
     *
     * @return void
     */
    public function test_update_existing_setting()
    {
        $user = User::factory()->owner()->create();
        $company = $user->owner->company;
        $this->assertEquals($user->owner->company_id, $company->id);

        // set as main owner
        $user->owner->is_prime_owner = true;
        $user->owner->save();
        $this->assertTrue($user->owner->fresh()->is_prime_owner);

        $this->actingAs($user);

        $module = SettingModule::Quotation;
        $setting = QuotationSettingFactory::new()->create([
            'company_id' => $company->id,
            'pagination' => 10
        ]);
        $this->assertEquals($company->id, $setting->company->id);

        $input = [
            'module' => $module,
            'pagination' => 50
        ];

        $response = $this->putJson(
            self::BASE_MODULE_URL . "/update",
            $input
        );

        // ensure that the value is updated
        $this->assertEquals($input['pagination'], $setting->fresh()->pagination);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) use ($user) {
            $json->has('setting');
            $json->where('setting.company_id', $user->owner->company_id);
            $json->has('status');
            $json->has('message');
        });
    }
}
