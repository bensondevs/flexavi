<?php

namespace Tests\Feature\Meta;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class WorkTest extends TestCase
{
    /**
     * Test get all work statuses
     *
     * @return void
     */
    public function test_get_all_work_statuses()
    {
        $response = $this->getJson('/api/meta/work/all_statuses');
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('1', 'Created');
            $json->where('2', 'In Process');
            $json->where('3', 'Finished');
            $json->where('4', 'Unfinished');
        });
    }
}
