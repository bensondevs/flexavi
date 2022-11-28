<?php

namespace Tests\Feature\Meta;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    /**
     * Test get all invoice statuses
     *
     * @return void
     */
    public function test_get_all_invoice_statuses()
    {
        $response = $this->getJson('/api/meta/invoice/all_statuses');
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('1', 'Draft');
            $json->where('2', 'Created');
            $json->where('3', 'Unpaid');
            $json->where('4', 'Down Payment');
            $json->where('5', 'Paid');
            $json->where('6', 'Payment Overdue');
            $json->where('7', 'First Reminder Sent');
            $json->where('8', 'First Reminder Overdue, send second reminder?');
            $json->where('9', 'Second Reminder Sent');
            $json->where('10', 'Second reminder overdue, send third reminder?');
            $json->where('11', 'Third Reminder Sent');
            $json->where('12', 'Third Reminder Overdue, sent debt collector?');
            $json->where('13', 'Debt Collector Sent');
            $json->where('14', 'Paid Via Debt Collector');
        });
    }

    /**
     * Test get all selectable invoice statuses
     *
     * @return void
     */
    public function test_get_all_selectable_invoice_statuses()
    {
        $response = $this->getJson('/api/meta/invoice/selectable_statuses');
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('7', 'First Reminder Sent');
            $json->where('8', 'First Reminder Overdue, send second reminder?');
            $json->where('9', 'Second Reminder Sent');
            $json->where('10', 'Second reminder overdue, send third reminder?');
            $json->where('11', 'Third Reminder Sent');
            $json->where('12', 'Third Reminder Overdue, sent debt collector?');
            $json->where('13', 'Debt Collector Sent');
            $json->where('14', 'Paid Via Debt Collector');
        });
    }

    /**
     * Test get all invoice payment methods
     *
     * @return void
     */
    public function test_all_payment_methods()
    {
        $response = $this->getJson('/api/meta/invoice/all_payment_methods');
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('1', 'Cash');
            $json->where('2', 'Bank Transfer');
        });
    }
}
