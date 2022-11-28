<?php

namespace Tests\Feature\Meta;

use App\Enums\Setting\SettingModule;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use WithFaker;

    /**
     * Test get all quotation types
     *
     * @return void
     */
    public function test_get_all_setting_modules(): void
    {
        $response = $this->getJson('/api/meta/setting/all_modules');

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            // Dashboard
            $json->where('1', 'Dashboard');
            $json->where('2', 'Employee');
            $json->where('3', 'Customer');
            $json->where('4', 'Invoice');
            $json->where('5', 'Quotation');
            $json->where('6', 'Company');
        });
    }
}
