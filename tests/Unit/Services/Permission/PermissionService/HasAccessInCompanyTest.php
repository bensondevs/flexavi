<?php

namespace Tests\Unit\Services\Permission\PermissionService;

use App\Models\User\User;

/**
 * @see \App\Services\Permission\PermissionService::hasAccessInCompany()
 *      To the tested service class method.
 */
class HasAccessInCompanyTest extends PermissionServiceTest
{
    public function it_aborts_when_user_doesnt_have_company(): void
    {
        //
    }

    public function it_aborts_when_user_from_other_company_try_to_access(): void
    {
        //
    }
}
