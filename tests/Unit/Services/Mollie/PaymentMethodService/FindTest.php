<?php

namespace Tests\Unit\Services\Mollie\PaymentMethodService;

use App\Services\Mollie\PaymentMethodService;
use Exception;
use Mollie\Api\Exceptions\ApiException;
use Mollie\Api\Resources\Method;

/**
 * @see PaymentMethodService::find()
 *      To the tested method.
 */
class FindTest extends PaymentMethodServiceTest
{
    /**
     * Possible IDs for the find method.
     *
     * @var string[]
     */
    private $possibleIds = [
        'paypal',
        'banktransfer',
        'creditcard',
    ];

    /**
     * Ensure the returned data is mollie api resource method.
     *
     * @test
     * @return void
     * @throws ApiException
     */
    public function it_returns_mollie_api_resource_method(): void
    {
        $service = $this->service();

        foreach ($this->possibleIds as $possibleId) {
            $return = $service->find($possibleId);
            $this->assertInstanceOf(Method::class, $return);
        }
    }

    /**
     * Ensure the returned data is blank when ID is invalid.
     *
     * @test
     * @return void
     */
    public function it_throws_exception_when_id_is_invalid(): void
    {
        $service = $this->service();

        try {
            $service->find('invalidid');
            $this->assertTrue(false);
        } catch (Exception $exception) {
            $this->assertNotNull($exception->getMessage());
        }
    }
}
