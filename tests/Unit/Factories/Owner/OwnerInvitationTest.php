<?php

namespace Tests\Unit\Factories\Owner;

use App\Models\Owner\OwnerInvitation;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OwnerInvitationTest extends TestCase
{
    use WithFaker;

    /**
     * Test create a company owner invitation instance
     *
     * @return void
     */
    public function test_create_company_owner_invitation_instance()
    {
        // make an instance
        $invitation = OwnerInvitation::factory()->create();

        // assert the instance
        $this->assertNotNull($invitation);
        $this->assertModelExists($invitation);
        $this->assertDatabaseHas('owner_invitations', [
            'id' => $invitation->id,
            'invited_email' => $invitation->invited_email,
            'registration_code' => $invitation->registration_code,
            'name' => $invitation->name,
            'phone' => $invitation->phone,
            'status' => $invitation->status,
            'expiry_time' => $invitation->expiry_time,
        ]);
    }

    /**
     * Test create multiple company owner invitation instances
     *
     * @return void
     */
    public function test_create_multiple_company_owner_invitation_instances()
    {
        // make the instances
        $count = 10;
        $invitations = OwnerInvitation::factory($count)->create();

        // assert the instances
        $this->assertTrue(count($invitations) === $count);
    }

    /**
     * Test update a company owner invitation instance
     *
     * @return void
     */
    public function test_update_company_owner_invitation_instance()
    {
        // make an instance
        $invitation = OwnerInvitation::factory()->create();

        // assert the instance
        $this->assertNotNull($invitation);
        $this->assertModelExists($invitation);
        $this->assertDatabaseHas('owner_invitations', [
            'id' => $invitation->id,
            'invited_email' => $invitation->invited_email,
            'registration_code' => $invitation->registration_code,
            'name' => $invitation->name,
            'phone' => $invitation->phone,
            'expiry_time' => $invitation->expiry_time,
        ]);

        // generate dummy data
        $invitedEmail = $this->faker->safeEmail;
        $name = $this->faker->name;
        $phone = $this->faker->phoneNumber;
        $expiryTime = Carbon::now()
            ->addDays(1)
            ->format('Y-m-d H:i:s');

        // update instance
        $invitation->update([
            'invited_email' => $invitedEmail,
            'name' => $name,
            'phone' => $phone,
            'expiry_time' => $expiryTime,
        ]);

        // assert the updated instance
        $this->assertDatabaseHas('owner_invitations', [
            'id' => $invitation->id,
            'registration_code' => $invitation->registration_code,
            'invited_email' => $invitedEmail,
            'name' => $name,
            'phone' => $phone,
            'expiry_time' => $expiryTime,
        ]);
    }

    /**
     * Test soft delete a company owner invitation instance
     *
     * @return void
     */
    public function test_soft_delete_company_owner_invitation_instance()
    {
        // make an instance
        $invitation = OwnerInvitation::factory()->create();

        // assert the instance
        $this->assertNotNull($invitation);
        $this->assertModelExists($invitation);
        $this->assertDatabaseHas('owner_invitations', [
            'id' => $invitation->id,
            'invited_email' => $invitation->invited_email,
            'registration_code' => $invitation->registration_code,
            'name' => $invitation->name,
            'phone' => $invitation->phone,
            'status' => $invitation->status,
            'expiry_time' => $invitation->expiry_time,
        ]);

        // soft delete the instance
        $invitation->delete();

        // assert the soft deleted instance
        $this->assertSoftDeleted($invitation);
    }

    /**
     * Test hard delete a company owner invitation instance
     *
     * @return void
     */
    public function test_hard_delete_company_owner_invitation_instance()
    {
        // make an instance
        $invitation = OwnerInvitation::factory()->create();

        // assert the instance
        $this->assertNotNull($invitation);
        $this->assertModelExists($invitation);
        $this->assertDatabaseHas('owner_invitations', [
            'id' => $invitation->id,
            'invited_email' => $invitation->invited_email,
            'registration_code' => $invitation->registration_code,
            'name' => $invitation->name,
            'phone' => $invitation->phone,
            'status' => $invitation->status,
            'expiry_time' => $invitation->expiry_time,
        ]);

        // hard delete the instance
        $invitationId = $invitation->id;
        $invitation->forceDelete();

        // assert the hard deleted instance
        $this->assertModelMissing($invitation);
        $this->assertDatabaseMissing('owner_invitations', [
            'id' => $invitationId,
        ]);
    }

    /**
     * Test restore a trashed company owner invitation instance
     *
     * @return void
     */
    public function test_restore_trashed_company_owner_invitation_instance()
    {
        // make an instance
        $invitation = OwnerInvitation::factory()->create();

        // assert the instance
        $this->assertNotNull($invitation);
        $this->assertModelExists($invitation);
        $this->assertDatabaseHas('owner_invitations', [
            'id' => $invitation->id,
            'invited_email' => $invitation->invited_email,
            'registration_code' => $invitation->registration_code,
            'name' => $invitation->name,
            'phone' => $invitation->phone,
            'status' => $invitation->status,
            'expiry_time' => $invitation->expiry_time,
        ]);

        // soft delete the instance
        $invitation->delete();

        // assert the soft deleted instance
        $this->assertSoftDeleted($invitation);

        // restore the trashed instance
        $invitation->restore();

        // assert the restored instance
        $this->assertNotSoftDeleted($invitation);
    }
}
