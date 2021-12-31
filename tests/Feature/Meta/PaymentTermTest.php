<?php

namespace Tests\Feature\Meta;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentTermTest extends TestCase
{
    /**
     * A payment term statuses test.
     *
     * @return void
     */
    public function test_all_invoice_statuses()
    {
        $url = '/api/meta/payment_term/all_statuses';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
    }
}
