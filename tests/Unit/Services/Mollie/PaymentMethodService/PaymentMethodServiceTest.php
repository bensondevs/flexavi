<?php

namespace Tests\Unit\Services\Mollie\PaymentMethodService;

use App\Services\Mollie\PaymentMethodService;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see PaymentMethodService
 *      To the tested service class.
 * @see https://meet.google.com/idz-tmqe-dwg?pli=1&authuser=1
 *      To the Google meet URL when the unit test is added.
 */
class PaymentMethodServiceTest extends TestCase
{
    use WithFaker;

    /**
     * Service class name used.
     *
     * @var string
     */
    private string $serviceClass = PaymentMethodService::class;

    /**
     * Service instance property container.
     *
     * @var PaymentMethodService
     */
    private $service;

    /**
     * Ensure the class is not crashing when encapsulated.
     *
     * @test
     * @return void
     */
    public function it_does_not_throw_any_error_when_encapsulated(): void
    {
        $this->assertInstanceOf($this->serviceClass, $this->service());
    }

    /**
     * Create or get service instance.
     *
     * @param bool $force
     * @return PaymentMethodService
     */
    public function service(bool $force = false): PaymentMethodService
    {
        if ($this->service instanceof PaymentMethodService and !$force) {
            return $this->service;
        }

        return $this->service = new PaymentMethodService();
    }
}
