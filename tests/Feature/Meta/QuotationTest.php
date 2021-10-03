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
        $headers = ['Accept' => 'application/json'];
        $url = '/api/meta/quotation/all_types';
        $response = $this->withHeaders($headers)->get($url);

        $response->assertStatus(200);
    }

    /**
     * Quotation statuses test.
     *
     * @return void
     */
    public function test_all_quotation_statuses()
    {
        $headers = ['Accept' => 'application/json'];
        $url = '/api/meta/quotation/all_statuses';
        $response = $this->withHeaders($headers)->get($url);

        $response->assertStatus(200);
    }

    /**
     * Quotation payment methods test.
     *
     * @return void
     */
    public function test_all_quotation_payment_methods()
    {
        $headers = ['Accept' => 'application/json'];
        $url = '/api/meta/quotation/all_payment_methods';
        $response = $this->withHeaders($headers)->get($url);

        $response->assertStatus(200);
    }

    /**
     * Quotation payment damage causes test.
     *
     * @return void
     */
    public function test_all_quotation_damage_causes()
    {
        $headers = ['Accept' => 'application/json'];
        $url = '/api/meta/quotation/all_damage_causes';
        $response = $this->withHeaders($headers)->get($url);

        $response->assertStatus(200);
    }

    /**
     * Quotation canceller test.
     *
     * @return void
     */
    public function test_all_quotation_cancellers()
    {
        $headers = ['Accept' => 'application/json'];
        $url = '/api/meta/quotation/all_cancellers';
        $response = $this->withHeaders($headers)->get($url);

        $response->assertStatus(200);
    }
}
