<?php

namespace Tests\Feature\Meta;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    /**
     * Employee types test.
     *
     * @return void
     */
    public function test_all_employee_types()
    {
        $url = '/api/meta/employee/all_types';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
    }

    /**
     * Employee statuses test.
     *
     * @return void
     */
    public function test_all_employee_statuses()
    {
        $url = '/api/meta/employee/all_employment_statuses';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
    }
}
