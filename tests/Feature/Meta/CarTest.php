<?php

namespace Tests\Feature\Meta;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CarTest extends TestCase
{
    /**
     * Collect all car statuses test.
     *
     * @return void
     */
    public function test_all_car_statuses()
    {
        $headers = ['Accept' => 'application/json'];
        $url = '/api/meta/car/all_statuses';
        $response = $this->withHeaders($headers)->get($url);

        $response->assertStatus(200);
    }
}