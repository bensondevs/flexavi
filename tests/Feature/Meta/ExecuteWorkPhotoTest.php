<?php

namespace Tests\Feature\Meta;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExecuteWorkPhotoTest extends TestCase
{
    /**
     * Execute work photo test.
     *
     * @return void
     */
    public function test_all_execute_work_types()
    {
        $headers = ['Accept' => 'application/json'];
        $url = '/api/meta/employee/all_types';
        $response = $this->withHeaders($headers)->get($url);

        $response->assertStatus(200);
    }
}