<?php

namespace App\Services\Mollie;

use Mollie\Api\Exceptions\ApiException;
use Mollie\Api\Resources\BaseResource;
use Mollie\Api\Resources\Method;
use Mollie\Laravel\Facades\Mollie;

/**
 * @see \Tests\Unit\Services\Mollie\PaymentMethodService\PaymentMethodServiceTest
 *      To the class unit tester folder.
 */
class PaymentMethodService
{
    /**
     * Retrieve a payment method from Mollie.
     *
     * @param string $methodId
     * @return BaseResource|Method
     * @throws ApiException
     * @see \Tests\Unit\Services\Mollie\PaymentMethodService\FindTest
     *      To the method unit tester.
     */
    public function find(string $methodId): BaseResource|Method
    {
        return Mollie::api()
            ->methods()
            ->get($methodId);
    }

    /**
     * Retrieve all active methods for the organization. In test mode, this includes pending methods.
     * The results are not paginated.
     *
     * @return array
     * @throws ApiException
     * @see \Tests\Unit\Services\Mollie\PaymentMethodService\GetTest
     *      To the method unit tester.
     */
    public function get(): array
    {
        $actives = Mollie::api()->methods()->allActive();

        return object_to_array($actives);
    }
}
