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
        $url = '/api/meta/cost/all_costable_types';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
    }
}
