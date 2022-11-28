<?php

namespace Tests\Integration\Dashboard\Company\Employee\EmployeeInvitation;

use App\Enums\EmployeeInvitation\EmployeeInvitationStatus;
use App\Http\Resources\Employee\EmployeeInvitationResource;
use App\Models\User\User;
use App\Traits\FeatureTestUsables;
use Database\Factories\EmployeeInvitationFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 *  @see App\Http\Controllers\Api\Company\Employee\EmployeeInvitationController::employeeInvitations()
 *      to the tested controller method
 */
class PopulateEmployeeInvitationTest extends TestCase
{
    use WithFaker;
    use FeatureTestUsables;

    /**
    * Test populate employee invitations by filter
    *
    * @return void
    */
    public function test_populate_employee_invitations_by_filter(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;

        $activeInvitation = EmployeeInvitationFactory::new()
            ->for($company)
            ->active()
            ->create();
        $cancelledInvitation = EmployeeInvitationFactory::new()
            ->for($company)
            ->cancelled()
            ->create();

        $response = $this->getJson(
            '/api/dashboard/companies/employees/invitations?' .
                'status=' . EmployeeInvitationStatus::Active
        );
        $response->assertStatus(200);
        $this->assertResponseAttributeIsPaginationInstance(
            $response,
            'invitations',
            EmployeeInvitationResource::class
        );

        $this->assertEquals(
            $activeInvitation->id,
            $response->json('invitations.data.0.id')
        );
        $this->assertEquals(
            EmployeeInvitationStatus::Active,
            $response->json('invitations.data.0.status')
        );
        $this->assertEquals(
            1,
            $response->json('invitations.total')
        );
    }

     /**
    * Test populate employee invitation by search keyword
    *
    * @return void
    */
    public function test_populate_employee_invitations_by_search_keyword(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;

        $keyword = 'Forger Helsinki';
        $matchedInvitation = EmployeeInvitationFactory::new()
            ->for($company)
            ->active()
            ->create(['name' => $keyword]);
        $unmatchedInvitation = EmployeeInvitationFactory::new()
            ->for($company)
            ->cancelled()
            ->create();

        $response = $this->getJson(
            "/api/dashboard/companies/employees/invitations?keyword=$keyword"
        );
        $response->assertStatus(200);
        $this->assertResponseAttributeIsPaginationInstance(
            $response,
            'invitations',
            EmployeeInvitationResource::class
        );

        $this->assertEquals(
            $matchedInvitation->id,
            $response->json('invitations.data.0.id')
        );
        $this->assertEquals(
            1,
            $response->json('invitations.total')
        );
    }
}
