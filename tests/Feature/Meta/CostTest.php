<?php

namespace Tests\Feature\Meta;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CostTest extends TestCase
{
    /**
     * Collect all cost costable types test.
     *
     * @return void
     */
    public function test_all_costable_types()
    {
        $headers = ['Accept' => 'application/json'];
        $url = '/api/meta/cost/all_costable_types';
        $response = $this->withHeaders($headers)->get($url);

        $response->assertStatus(200);
    }
}
