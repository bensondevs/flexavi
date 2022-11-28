<?php

namespace Tests\Feature\Meta;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    /**
     * Test get all employee types
     *
     * @return void
     */
    public function test_get_all_employee_types(): void
    {
        $response = $this->getJson('/api/meta/employee/all_types');
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('1', 'Administrative');
            $json->where('2', 'Roofer');
        });
    }

    /**
     * test get all employee statuses
     *
     * @return void
     */
    public function test_get_all_employee_statuses(): void
    {
        $response = $this->getJson(
            '/api/meta/employee/all_employment_statuses'
        );
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('1', 'Active');
            $json->where('2', 'Inactive');
        });
    }
}
