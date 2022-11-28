<?php

namespace Tests\Unit\Services\Template\TemplateService;

use App\Services\Template\TemplateService;
use Tests\TestCase;

/**
 * @see \App\Services\Template\TemplateService
 *      To the tested service class.
 * @see https://app.clickup.com/t/34505pm
 *      To view tickets when they were created
 */
class TemplateServiceTest extends TestCase
{
    /**
     * Service instance container property.
     *
     * @var ?TemplateService
     */
    private ?TemplateService $service = null;

    /**
     * Test basic for making sure the class is not crashing.
     *
     * @test
     * @return void
     */
    public function it_does_not_have_stupid_error(): void
    {
        $this->assertInstanceOf(
            TemplateService::class,
            $this->service()
        );
    }

    /**
     * Create or get service instance
     *
     * @param bool $force
     * @return TemplateService
     */
    protected function service(bool $force = false): TemplateService
    {
        if ($this->service instanceof TemplateService and !$force) {
            return $this->service;
        }

        return $this->service = new TemplateService();
    }
}
