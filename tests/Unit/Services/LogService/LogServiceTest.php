<?php

namespace Tests\Unit\Services\LogService;

use App\Models\Customer\Customer;
use App\Models\Log\Log;
use App\Models\User\User;
use App\Services\Log\LogService;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Services\Log\LogService
 *      To see the class test
 */
class LogServiceTest extends TestCase
{
    use WithFaker;

    /**
     * Test basic for making sure the class is not crashing.
     *
     * @test
     * @return void
     */
    public function test_parse_array_value(): void
    {
        $subject = Customer::factory()->create();
        $user = User::factory()->owner()->create();
        $log = Log::factory()->name('customer.updates.fullname')->subject($subject)->causer($user)->create([
            'parameter_values' => ['customerId' => $subject->id, 'causerId' => $user->id],
            'required_parameters' => ['causerId', 'customerId']
        ]);
        $this->assertNotNull(LogService::formatMessageWithTemplatingService($log));
    }

    /**
     * Test basic for making sure the class is not crashing.
     *
     * @test
     * @return void
     */
    public function test_parse_null_value(): void
    {
        $subject = Customer::factory()->create();
        $user = User::factory()->owner()->create();
        $log = Log::factory()->name('customer.updates.fullname')->subject($subject)->causer($user)->create([
            'parameter_values' => ['customerId' => $subject->id, 'causerId' => $user->id],
            'required_parameters' => ['causerId', 'customerId']
        ]);
        $log->parameter_values = null;
        $log->saveQuietly();
        $log = $log->fresh();
        $this->assertNotNull(LogService::formatMessageWithTemplatingService($log));
    }

}
