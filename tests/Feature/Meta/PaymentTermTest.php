<?php

namespace Tests\Feature\Meta;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class PaymentTermTest extends TestCase
{
    /**
     * Test get all payment term statuses
     *
     * @return void
     */
    public function test_get_all_payment_term_statuses()
    {
        $response = $this->getJson('/api/meta/payment_term/all_statuses');
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('1', 'Unpaid');
            $json->where('2', 'Paid');
            $json->where('3', 'Forwarded to Debt Collector');
            $json->where('4', 'Paid via debt collector');
        });
    }
}
