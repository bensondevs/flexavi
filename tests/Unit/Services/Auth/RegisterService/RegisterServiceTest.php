<?php

namespace Tests\Unit\Services\Auth\RegisterService;

use App\Services\Auth\RegisterService;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterServiceTest extends TestCase
{
    use WithFaker;

    /**
     * Register service instance container property.
     *
     * @var RegisterService|null
     */
    private ?RegisterService $registerService = null;

    /**
     * Create or get register service class instance.
     *
     * @param bool $force
     * @return RegisterService
     */
    protected function registerService(bool $force = false): RegisterService
    {
        if ($this->registerService instanceof RegisterService) {
            return $this->registerService;
        }

        return $this->registerService = app(RegisterService::class);
    }

    /**
     * Ensure the register service encapsulation works.
     *
     * @test
     * @return void
     */
    public function it_can_encapsulate_correctly(): void
    {
        $registerService = $this->registerService(true);
        $this->assertInstanceOf(RegisterService::class, $registerService);
    }
}
