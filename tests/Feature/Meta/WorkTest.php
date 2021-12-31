<?php

namespace Tests\Feature\Meta;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WorkTest extends TestCase
{
    /**
     * A work statuses test.
     *
     * @return void
     */
    public function test_all_work_statuses()
    {
        $url = '/api/meta/work/all_statuses';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
    }
}
