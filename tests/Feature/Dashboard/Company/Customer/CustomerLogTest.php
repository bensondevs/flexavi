<?php

namespace Tests\Feature\Dashboard\Company\Customer;

use App\Models\Customer\Customer;
use App\Models\Log\Log;
use App\Models\User\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\Company\Customer\CustomerLogController
 *      To the tested controller class.
 */
class CustomerLogTest extends TestCase
{
    use WithFaker;

    /**
     * Module base URL.
     *
     * @const
     */
    const MODULE_BASE_URL = '/api/dashboard/companies/customers/logs';

    /**
     * Test populate customer logs.
     *
     * @test
     * @return void
     */
    public function test_populate_customer_logs(): void
    {
        $user = User::factory()->owner()->create();
        $this->actingAs($user);

        $customer = Customer::factory(state: [
            'company_id' => $user->company->id
        ])->create();

        // Populate logs
        $customerLogs = Log::factory(10, [
            'company_id' => $customer->company_id,
            'log_name' => 'customer.' . $this->faker->randomElement(['created', 'updated', 'deleted']),
            'causer_id' => $user->id,
            'causer_type' => User::class,
            'subject_id' => $customer->id,
            'subject_type' => Customer::class,
            'required_parameters' => ['causerId', 'customerId'],
            'parameter_values' => [
                'causerId' => $user->id,
                'customerId' => $customer->id,
            ],
            'description' => $this->faker->sentence(5),
            'created_at' => now(),
            'updated_at' => now(),
        ])->create();

        $response = $this->getJson(urlWithParams(self::MODULE_BASE_URL, [
            'customer_id' => $customer->id,
        ]));
        $response->assertOk();

        $content = $response->getOriginalContent();
        $this->assertArrayHasKey('logs', $content);

        // Check current date should be shown
        $returnedLogs = $content['logs']->toArray()['data'];
        $currentDate = now()->format('Y-m-d');
        $this->assertArrayHasKey($currentDate, $returnedLogs);

        // Query all the expected results
        $todayLogs = $returnedLogs[$currentDate][array_key_first($returnedLogs[$currentDate])];
        $this->assertTrue($customerLogs->count() <= $todayLogs);
        foreach ($todayLogs as $todayLog) {
            $found = $customerLogs->where('id', $todayLog['id']);
            $this->assertNotNull($found);
        }
    }
}
