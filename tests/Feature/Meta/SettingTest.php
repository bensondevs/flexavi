<?php

namespace Tests\Feature\Meta;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class SettingTest extends TestCase
{
    /**
     * Collect all setting types test.
     *
     * @return void
     */
    public function test_all_setting_types()
    {
        $url = '/api/meta/setting/all_types';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
    }
}
