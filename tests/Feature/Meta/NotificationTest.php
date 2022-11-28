<?php

namespace Tests\Feature\Meta;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    /**
     * Test get all notification types
     *
     * @return void
     */
    public function test_get_all_notification_types()
    {
        $response = $this->getJson('/api/meta/notification/all_types');
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where(1, 'Dashboard');
            $json->where(2, 'Company');
            $json->where(3, 'Employee');
            $json->where(4, 'Customer');
            $json->where(5, 'Fleet');
            $json->where(6, 'Works');
            $json->where(7, 'Workday');
            $json->where(8, 'Invoice');
            $json->where(9, 'Quotation');
            $json->where(10, 'Owner');
            $json->where(11, 'Log');
        });
    }

    /**
     * Test get all notification populate types
     *
     * @return void
     */
    public function test_get_all_notification_populate_types()
    {
        $response = $this->getJson('/api/meta/notification/all_populate_types');
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where(1, 'Today');
            $json->where(2, 'Last3 days');
            $json->where(3, 'Last7 days');
            $json->where(4, 'Last30 days');
            $json->where(5, 'This year');
        });
    }
}
