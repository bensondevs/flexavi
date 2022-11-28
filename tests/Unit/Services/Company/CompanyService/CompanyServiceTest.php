<?php

namespace Tests\Unit\Services\Company\CompanyService;

use App\Services\Company\CompanyService;
use Tests\TestCase;

/**
 * @see \App\Services\Company\CompanyService
 *      To the tested service class.
 */
class CompanyServiceTest extends TestCase
{
    /**
     * Company service class instance container property.
     *
     * @var CompanyService|null
     */
    private ?CompanyService $companyService = null;

    /**
     * Create or get company service class.
     *
     * @param bool $force
     * @return CompanyService
     */
    protected function companyService(bool $force = false): CompanyService
    {
        if ($this->companyService instanceof CompanyService and !$force) {
            return $this->companyService;
        }

        return $this->companyService = app(CompanyService::class);
    }

    /**
     * Ensure the service class encapsulated successfully.
     *
     * @test
     * @return void
     */
    public function it_encapsulates_successfully(): void
    {
        $companyService = $this->companyService(true);
        $this->assertInstanceOf(CompanyService::class, $companyService);
    }
}
