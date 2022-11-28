<?php

namespace Tests\Integration\Dashboard\Company\Owner\OwnerInvitation;

use App\Models\User\User;
use App\Traits\FeatureTestUsables;
use Database\Factories\OwnerFactory;
use Database\Factories\OwnerInvitationFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 *  @see App\Http\Controllers\Api\Company\Owner\OwnerInvitationController::ownerInvitations()
 *      to the tested controller method
 */
class PopulateOwnerInvitationTest extends TestCase
{
    use WithFaker;
    use FeatureTestUsables;

    /**
     * Module base URL container constant.
     *
     * @const
     */
    public const MODULE_BASE_URL = '/api/dashboard/companies/owners/invitations';

    /**
    * Test populate owner invitations by search keyword
    *
    * @return void
    */
    public function test_populate_owner_invitations_by_search_keyword(): void
    {
        $user = $this->authenticateAsOwner();
        $company = $user->owner->company;

        $keyword = "Jack Dorsey";
        $matchedOwnerInvitations = OwnerInvitationFactory::new()
            ->for($company)
            ->active()
            ->count(2)
            ->create(['name' => $keyword]);
        $unmatchOwnerInvitations = OwnerInvitationFactory::new()
            ->for($company)
            ->active()
            ->count(2)
            ->create();

        $response = $this->getJson(self::MODULE_BASE_URL."?keyword=$keyword");

        $this->assertResponseAttributeIsPaginationInstance($response, 'invitations');

        $content = $response->getOriginalContent();
        $ownerInvitations = $content['invitations'];
        $this->assertEquals($matchedOwnerInvitations->count(), $ownerInvitations->count());
    }
}
