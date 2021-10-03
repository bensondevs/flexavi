<?php

namespace Tests\Feature\Meta;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    /**
     * An invoice statuses test.
     *
     * @return void
     */
    public function test_all_invoice_statuses()
    {
        $headers = ['Accept' => 'application/json'];
        $url = '/api/meta/invoice/all_statuses';
        $response = $this->withHeaders($headers)->get($url);

        $response->assertStatus(200);
    }

    /**
     * A selectable invoice statuses test.
     *
     * @return void
     */
    public function test_selectable_invoice_statuses()
    {
        $headers = ['Accept' => 'application/json'];
        $url = '/api/meta/invoice/selectable_statuses';
        $response = $this->withHeaders($headers)->get($url);

        $response->assertStatus(200);
    }

    /**
     * An invoice statuses test.
     *
     * @return void
     */
    public function test_all_payment_methods()
    {
        $headers = ['Accept' => 'application/json'];
        $url = '/api/meta/invoice/all_payment_methods';
        $response = $this->withHeaders($headers)->get($url);

        $response->assertStatus(200);
    }
}
