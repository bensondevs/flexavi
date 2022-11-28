<?php

namespace Tests\Unit\Services\Mollie\PaymentMethodService;

use App\Services\Mollie\PaymentMethodService;
use Mollie\Api\Exceptions\ApiException;

/**
 * @see PaymentMethodService::get()
 *      To the tested method.
 */
class GetTest extends PaymentMethodServiceTest
{
    /**
     * Ensure the method is returning array all the time.
     *
     * @test
     * @return void
     * @throws ApiException
     */
    public function it_returns_array(): void
    {
        $service = $this->service();
        $return = $service->get();

        $this->assertIsArray($return);
    }

    /**
     * Ensure the returned array contains arrays which has id.
     *
     * @test
     * @return void
     * @throws ApiException
     */
    public function it_returns_array_contains_ids(): void
    {
        $service = $this->service();
        $return = $service->get();

        foreach ($return as $returnedItem) {
            $this->assertArrayHasKey('id', $returnedItem);
        }
    }
}
