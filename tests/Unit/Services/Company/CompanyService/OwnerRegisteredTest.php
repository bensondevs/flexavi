<?php

namespace Tests\Unit\Services\Company\CompanyService;

use App\Enums\Role;
use App\Models\Company\Company;
use App\Models\Owner\Owner;
use App\Models\User\User;

/**
 * @see \App\Services\Company\CompanyService::ownerRegistered()
 *      To the tested method.
 */
class OwnerRegisteredTest extends CompanyServiceTest
{
    /**
     * Prepare sole user -- this user will have no role and company.
     *
     * @return User
     */
    private function prepareSoleUser(): User
    {
        $user = User::factory()->create();
        $user->removeRole(Role::Owner);

        $owner = Owner::whereUserId($user->id)->firstOrFail();
        Company::whereId($owner->company_id)->delete();
        $owner->forceDelete();

        return $user;
    }

    /**
     * Ensure the method creates empty company instance.
     *
     * @test
     * @return void
     */
    public function it_creates_empty_company_instance(): void
    {
        $user = $this->prepareSoleUser();

        $companyService = $this->companyService(true);
        $company = $companyService->ownerRegistered($user);

        $this->assertInstanceOf(Company::class, $company);

        $this->assertEquals($user->fullname, $company->company_name);
        $this->assertEquals($user->email, $company->email);
    }
}
