<?php

namespace Tests\Unit\Factories\Owner;

use App\Models\Owner\Owner;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OwnerTest extends TestCase
{
    use WithFaker;

    /**
     * Test create a company owner instance
     *
     * @return void
     */
    public function test_create_company_owner_instance()
    {
        // make an instance
        $owner = Owner::factory()->create();

        // assert the instance
        $this->assertNotNull($owner);
        $this->assertModelExists($owner);
        $this->assertDatabaseHas('owners', [
            'company_id' => $owner->company_id,
            'user_id' => $owner->user_id,
            'is_prime_owner' => $owner->is_prime_owner,
        ]);
    }

    /**
     * Test create multiple company owner instances
     *
     * @return void
     */
    public function test_create_multiple_company_owner_instances()
    {
        // make the instances
        $count = 10;
        $owners = Owner::factory($count)->create();

        // assert the instances
        $this->assertTrue(count($owners) === $count);
    }


    /**
     * Test soft delete a company owner instance
     *
     * @return void
     */
    public function test_soft_delete_company_owner_instance()
    {
        // make an instance
        $owner = Owner::factory()->create();

        // assert the instance
        $this->assertNotNull($owner);
        $this->assertModelExists($owner);
        $this->assertDatabaseHas('owners', [
            'company_id' => $owner->company_id,
            'user_id' => $owner->user_id,
            'is_prime_owner' => $owner->is_prime_owner,
        ]);

        // soft delete the instance
        $owner->delete();

        // assert the soft deleted instance
        $this->assertSoftDeleted($owner);
    }

    /**
     * Test hard delete a company owner instance
     *
     * @return void
     */
    public function test_hard_delete_company_owner_instance()
    {
        // make an instance
        $owner = Owner::factory()->create();

        // assert the instance
        $this->assertNotNull($owner);
        $this->assertModelExists($owner);
        $this->assertDatabaseHas('owners', [
            'company_id' => $owner->company_id,
            'user_id' => $owner->user_id,
            'is_prime_owner' => $owner->is_prime_owner,
        ]);

        // hard delete the instance
        $ownerId = $owner->id;
        $owner->forceDelete();

        // assert the hard deleted instance
        $this->assertModelMissing($owner);
        $this->assertDatabaseMissing('owners', [
            'id' => $ownerId,
        ]);
    }

    /**
     * Test restore a trashed company owner instance
     *
     * @return void
     */
    public function test_restore_trashed_company_owner_instance()
    {
        // make an instance
        $owner = Owner::factory()->create();

        // assert the instance
        $this->assertNotNull($owner);
        $this->assertModelExists($owner);
        $this->assertDatabaseHas('owners', [
            'company_id' => $owner->company_id,
            'is_prime_owner' => $owner->is_prime_owner,
            'user_id' => $owner->user_id,
        ]);

        // soft delete the instance
        $owner->delete();

        // assert the soft deleted instance
        $this->assertSoftDeleted($owner);

        // restore the trashed instance
        $owner->restore();

        // assert the restored instance
        $this->assertNotSoftDeleted($owner);
    }
}
