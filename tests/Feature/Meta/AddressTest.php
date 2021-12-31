<?php

namespace Tests\Feature\Meta;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AddressTest extends TestCase
{
    /**
     * Collect address types test.
     *
     * @return void
     */
    public function test_all_address_types()
    {
        $url = '/api/meta/address/all_address_types';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
    }
}
