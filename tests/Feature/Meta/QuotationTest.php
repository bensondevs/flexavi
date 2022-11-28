<?php

namespace Tests\Feature\Meta;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class QuotationTest extends TestCase
{
    /**
     * Test get all quotation types
     *
     * @return void
     */
    public function test_get_all_quotation_types(): void
    {
        $response = $this->getJson('/api/meta/quotation/all_types');
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('1', 'Leakage');
            $json->where('2', 'Renovation');
            $json->where('3', 'Reparation');
            $json->where('4', 'Renewal');
        });
    }

    /**
     * Test get all quotation statuses
     *
     * @return void
     */
    public function test_get_all_quotation_statuses(): void
    {
        $response = $this->getJson('/api/meta/quotation/all_statuses');

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('1', 'Drafted');
            $json->where('2', 'Sent');
            $json->where('3', 'Nullified');
            $json->where('4', 'Signed');
        });
    }

    /**
     * Test get all quotation payment methods
     *
     * @return void
     */
    public function test_get_all_quotation_payment_methods(): void
    {
        $response = $this->getJson('/api/meta/quotation/all_payment_methods');
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('1', 'Cash');
            $json->where('2', 'Bank Transfer');
        });
    }

    /**
     * Test get all quotation payment damage causes
     *
     * @return void
     */
    public function test_get_all_quotation_damage_causes(): void
    {
        $response = $this->getJson('/api/meta/quotation/all_damage_causes');
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('1', 'Leak');
            $json->where('2', 'Fungus / Mold');
            $json->where('3', 'Bird Nuisance');
            $json->where('4', 'Storm Damage');
            $json->where('5', 'Overdue Maintenance');
        });
    }

    /**
     * Test get all quotation cancellers
     *
     * @return void
     */
    public function test_get_all_quotation_cancellers(): void
    {
        $response = $this->getJson('/api/meta/quotation/all_cancellers');
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('1', 'Company');
            $json->where('2', 'Customer');
        });
    }
}
