<?php

namespace Tests\Feature\Meta;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class QuotationTest extends TestCase
{
    /**
     * Quotation types test.
     *
     * @return void
     */
    public function test_all_quotation_types()
    {
        $url = '/api/meta/quotation/all_types';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
    }

    /**
     * Quotation statuses test.
     *
     * @return void
     */
    public function test_all_quotation_statuses()
    {
        $url = '/api/meta/quotation/all_statuses';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
    }

    /**
     * Quotation payment methods test.
     *
     * @return void
     */
    public function test_all_quotation_payment_methods()
    {
        $url = '/api/meta/quotation/all_payment_methods';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
    }

    /**
     * Quotation payment damage causes test.
     *
     * @return void
     */
    public function test_all_quotation_damage_causes()
    {
        $url = '/api/meta/quotation/all_damage_causes';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
    }

    /**
     * Quotation canceller test.
     *
     * @return void
     */
    public function test_all_quotation_cancellers()
    {
        $url = '/api/meta/quotation/all_cancellers';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
    }
}
