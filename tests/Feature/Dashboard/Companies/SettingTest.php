<?php

namespace Tests\Feature\Dashboard\Companies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ Owner, Company, Setting };

class SettingTest extends TestCase
{
    /**
     * Base url of setting module
     * 
     * @var string
     */
    private $baseUrl = '/api/dashboard/companies/settings';

    /**
     * A populate company settings test.
     *
     * @return void
     */
    public function test_populate_company_settings()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('values');
        });
    }

    /**
     * A set value to company setting test.
     * 
     * @return void 
     */
    public function test_set_value_company_setting()
    {
        //
    }

    /**
     * A reset one setting type test
     * 
     * @return void
     */
    public function test_reset_type_default_setting()
    {
        //
    }

    /**
     * A reset all setting type test
     * 
     * @return void
     */
    public function test_reset_all_default_setting()
    {
        //
    }
}
