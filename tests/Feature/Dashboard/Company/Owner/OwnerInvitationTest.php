<?php

namespace Tests\Feature\Dashboard\Company\Owner;

use App\Models\Owner\OwnerInvitation;
use App\Models\User\User;
use Database\Factories\OwnerInvitationFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class OwnerInvitationTest extends TestCase
{
    use WithFaker;

    /**
     * Test populate company owner invitations
     *
     * @return void
     */
    public function test_populate_company_owner_invitations(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $response = $this->getJson(
            '/api/dashboard/companies/owners/invitations'
        );
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('invitations');
            $json->whereType('invitations.data', 'array');

            // pagination meta
            $json->has('invitations.current_page');
            $json->has('invitations.first_page_url');
            $json->has('invitations.from');
            $json->has('invitations.last_page');
            $json->has('invitations.last_page_url');
            $json->has('invitations.links');
            $json->has('invitations.next_page_url');
            $json->has('invitations.path');
            $json->has('invitations.per_page');
            $json->has('invitations.prev_page_url');
            $json->has('invitations.to');
            $json->has('invitations.total');
        });
    }

    /**
     * Test get a company owner invitation
     *
     * @return void
     */
    public function test_get_company_owner_invitation(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $ownerInvitation = OwnerInvitation::factory()->for($user->owner->company)->create();
        $response = $this->getJson(
            "/api/dashboard/companies/owners/invitations/view?id=$ownerInvitation->id"
        );
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('invitation');
            $json->has('invitation.id');
        });
    }

    /**
     * Test store a company owner invitation
     *
     * @return void
     */
    public function test_store_company_owner_invitation(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $response = $this->postJson(
            '/api/dashboard/companies/owners/invitations/store',
            [
                'invited_email' => $this->faker->safeEmail,
                'name' => $this->faker->name,
                'phone' => $this->faker->phoneNumber,
            ]
        );
        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('invitation');
            $json->has('invitation.id');

            // status meta
            $json->where('status', 'success');
            $json->has('message');
        });
    }

     /**
     * Test should fail store company owner invitation on already created invitation
     *
     * @return void
     */
    public function test_should_fail_store_company_owner_invitation_on_already_created_invitation(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $ownerInvitationData = [
            'invited_email' => $this->faker->safeEmail,
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
        ];
        // create an already created active invitation
        OwnerInvitationFactory::new()->active()->create($ownerInvitationData);

        $response = $this->postJson(
            '/api/dashboard/companies/owners/invitations/store',
            $ownerInvitationData
        );

        $response->assertStatus(422);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->has('errors');
        });
    }


     /**
     * Test should fail store company owner invitation on already registered user
     *
     * @return void
     */
    public function test_should_fail_store_company_owner_invitation_on_already_registered_user(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $alreadyRegisteredUser = User::factory()->create();

        $response = $this->postJson(
            '/api/dashboard/companies/owners/invitations/store',
            [
                'invited_email' => $alreadyRegisteredUser->email,
                'name' => $alreadyRegisteredUser->name,
                'phone' => $alreadyRegisteredUser->phone,
            ]
        );

        $response->assertStatus(422);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->has('errors');
        });
    }


    /**
     * Test cancel a company owner invitation
     *
     * @return void
     */
    public function test_cancel_company_owner_invitation(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );
        $ownerInvitation = OwnerInvitation::factory()->for($user->owner->company)->create();
        $response = $this->deleteJson(
            '/api/dashboard/companies/owners/invitations/cancel',
            [
                'id' => $ownerInvitation->id,
            ]
        );
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            // status meta
            $json->where('status', 'success');
            $json->has('message');
        });
    }

    /**
     * Test cancel a company owner invitation
     *
     * @return void
     */
    public function test_populate_available_permissions_for_invited_owner(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );
        $response = $this->getJson(
            '/api/dashboard/companies/owners/invitations/permissions'
        );
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            // status meta
            $json->has('modules');
        });
    }
}
