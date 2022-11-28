<?php

namespace Tests\Integration\Dashboard\Company\Owner\OwnerInvitation;

use App\Http\Resources\Owner\OwnerInvitationResource;
use App\Jobs\Owner\OwnerInvitation as OwnerInvitationJob;
use App\Models\Owner\OwnerInvitation;
use App\Models\Permission\Permission;
use App\Models\User\User;
use App\Traits\FeatureTestUsables;
use Database\Factories\OwnerInvitationFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 *  @see App\Http\Controllers\Api\Company\Owner\OwnerInvitationController::store()
 *      to the tested controller
 */
class StoreOwnerInvitationTest extends TestCase
{
    use FeatureTestUsables;
    use WithFaker;

    /**
     * Store module URL container constant.
     *
     * @const
     */
    public const BASE_MODULE_URL = '/api/dashboard/companies/owners/invitations';

    /**
    * Test with complex things and flows
    *
    * @return void
    */
    public function test_do_complex_things(): void
    {
        Queue::fake();
        $user = $this->authenticateAsOwner();

        $permissionNames = Permission::inRandomOrder()
            ->limit(10)
            ->get(['name'])
            ->pluck('name')
            ->toArray();

        // invitation data
        $input = [
            'invited_email' => $this->faker->email,
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'permission_names' => $permissionNames
        ];

        // make a request to create and send invitation
        $response = $this->postJson(
            self::BASE_MODULE_URL . '/store',
            $input
        )->assertStatus(201);
        $this->assertResponseStatusSuccess($response);
        $this->assertInstanceReturnedInResponse($response, 'invitation', OwnerInvitationResource::class);

        // assert that the invitation mail is sent
        Queue::assertPushed(OwnerInvitationJob::class);

        $invitation = OwnerInvitation::whereInvitedEmail($input['invited_email'])
            ->whereName($input['name'])
            ->wherePhone($input['phone'])
            ->first();

        // ensure that the invitation is created after the api request
        $this->assertNotNull($invitation);
        // ensure that invited user instace is not created before the invitation has been accepted by the invited user
        $this->assertNull($invitation->invitedUser);

        // make another request to accept the invitation
        $this->get(self::BASE_MODULE_URL . "/accept?code=$invitation->registration_code")
             ->assertStatus(302);

        // after the request was done ensure that the invited user instance is created
        $invitedUser = $invitation->fresh()->invitedUser;
        $this->assertNotNull($invitedUser);

        // get the permission names of the created invited user instance
        $invitedUserPermissionNames = $invitedUser
            ->permissions()
            ->get(['name'])
            ->pluck('name')
            ->toArray();

        // ensure that the created invited user instance has permissions
        foreach ($permissionNames as  $permissionId) {
            $this->assertContains($permissionId, $invitedUserPermissionNames);
        }

        // ensure length equals
        $this->assertEquals(count($permissionNames), count($invitedUserPermissionNames));
    }

    /**
    * Test invite owner to the company
    *
    * @return void
    */
    public function test_invite_owner_to_the_company(): void
    {
        Queue::fake();
        $user = $this->authenticateAsOwner();

        $ownerInvitationData = [
            'invited_email' => $this->faker->email,
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
        ];

        $response = $this->postJson(
            self::BASE_MODULE_URL . '/store',
            $ownerInvitationData
        );

        $this->assertDatabaseHas((new OwnerInvitation())->getTable(), $ownerInvitationData);
        Queue::assertPushed(OwnerInvitationJob::class);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('invitation');
            $json->has('status');
            $json->has('message');
        });
    }

    /**
    * Test reinvite owner that was invited but the invitation is already expired
    *
    * @return void
    */
    public function test_reinvite_owner_that_was_invited_but_the_invitation_is_already_expired(): void
    {
        Queue::fake();
        $user = $this->authenticateAsOwner();

        $ownerInvitationData = [
            'invited_email' => $this->faker->email,
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
        ];

        // create an already expired invitation
        $invitation = OwnerInvitationFactory::new()->expired()->create($ownerInvitationData);

        $response = $this->postJson(
            self::BASE_MODULE_URL . '/store',
            $ownerInvitationData
        );

        $this->assertDatabaseHas((new OwnerInvitation())->getTable(), $ownerInvitationData);
        Queue::assertPushed(OwnerInvitationJob::class);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('invitation');
            $json->has('status');
            $json->has('message');
        });
    }

    /**
    * Test should fail when invite owner that has already active invitation
    *
    * @return void
    */
    public function test_should_fail_when_invite_owner_that_has_already_active_invitation(): void
    {
        $user = $this->authenticateAsOwner();

        $ownerInvitationData = [
            'invited_email' => $this->faker->safeEmail,
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
        ];
        // create an already created active invitation
        OwnerInvitationFactory::new()->active()->create($ownerInvitationData);

        $response = $this->postJson(
            self::BASE_MODULE_URL . '/store',
            $ownerInvitationData
        );

        $response->assertStatus(422);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->has('errors');
        });
    }


     /**
     * Test should fail when inviting already registered owner
     *
     * @return void
     */
    public function test_should_fail_when_inviting_already_registered_owner(): void
    {
        $user = $this->authenticateAsOwner();

        $alreadyRegisteredUser = User::factory()->owner()->create();

        $response = $this->postJson(
            self::BASE_MODULE_URL . '/store',
            [
                'invited_email' => $alreadyRegisteredUser->email,
                'name' => $alreadyRegisteredUser->name,
                'phone' => $alreadyRegisteredUser->phone,
            ]
        );

        $this->assertDatabaseMissing((new OwnerInvitation())->getTable(), [
            "invited_email" => $alreadyRegisteredUser->email
        ]);

        $response->assertStatus(422);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->has('errors');
        });
    }

    /**
     * it should fail when set "expiry time" less than or equal to "today"
     *
     * @return void
     */
    public function test_set_expiry_time_must_be_after_today(): void
    {
        Queue::fake();
        $user = $this->authenticateAsOwner();

        $ownerInvitationData = [
            'expiry_time' => now()->startOfDay(),
            'invited_email' => $this->faker->email,
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
        ];

        $response = $this->postJson(
            self::BASE_MODULE_URL . '/store',
            $ownerInvitationData
        );

        $this->assertDatabaseMissing((new OwnerInvitation())->getTable(), $ownerInvitationData);
        Queue::assertNotPushed(OwnerInvitationJob::class);

        $response->assertStatus(422);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->has('errors');
            $json->whereType('errors.expiry_time', 'array');
        });
    }
}
