<?php

namespace Tests\Integration\Dashboard\Company\Owner;

use App\Http\Resources\Owner\OwnerResource;
use App\Models\Owner\Owner;
use App\Traits\FeatureTestUsables;
use Database\Factories\CompanyFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 *  @see App\Http\Controllers\Api\Company\Owner\OwnerController::makeAsMainOwner()
 *      to the tested controller
 */
class MakeAsMainOwnerTest extends TestCase
{
    use WithFaker;
    use FeatureTestUsables;

    /**
     * module URL container constant.
     *
     * @const
     */
    public const BASE_MODULE_URL = '/api/dashboard/companies/owners/main_owner';

    /**
    * Test make owner as main owner
    *
    * @return void
    */
    public function test_make_owner_as_main_owner(): void
    {
        $company = CompanyFactory::new()->create();
        $oldOwner = (function () use ($company) {
            $user = UserFactory::new()->owner()->create();
            $owner = $user->owner;
            $owner->company_id = $company->id;
            $owner->is_prime_owner = 1;
            $owner->save();
            return $owner;
        })();

        $oldOwnerPermissionIds = $oldOwner->user->permissions->pluck('id')->toArray();

        $this->actingAs($oldOwner->user);

        $newOwner = (function () use ($company) {
            $user = UserFactory::new()->owner($company)->create();
            $user->syncPermissions([]);
            $owner = $user->owner;
            $owner->company_id = $company->id;
            $owner->is_prime_owner = 0 ;
            $owner->save();
            return $owner;
        })();

        $this->assertTrue($newOwner->fresh()->user->permissions->isEmpty());

        // ensure owners prime and no prime are on place
        $this->assertTrue($oldOwner->fresh()->is_prime_owner);
        $this->assertFalse($newOwner->fresh()->is_prime_owner);

        $response = $this->putJson(self::BASE_MODULE_URL, [
            'id' => $newOwner->id,
        ]);
        $response->assertStatus(200);
        $this->assertInstanceReturnedInResponse($response, 'owner', OwnerResource::class);
        $this->assertResponseStatusSuccess($response);

        $this->assertEquals(
            1,
            Owner::query()
                ->whereCompanyId($oldOwner->company->id)
                ->primeOnly()
                ->count()
        );

        // ensure the old owner permission is still exists
        foreach ($oldOwner->fresh()->user->permissions->pluck('id') as $permissionId) {
            $this->assertTrue(
                in_array(
                    $permissionId,
                    $oldOwnerPermissionIds
                )
            );
        }

        // get new owner permission ids
        $newOwnerPermissionIds =  $newOwner->fresh()->user->permissions->pluck('id')->toArray();

        // ensure the old owner permissions is moved to the new owner
        foreach ($oldOwnerPermissionIds as $permissionId) {
            $this->assertTrue(
                in_array(
                    $permissionId,
                    $newOwnerPermissionIds
                )
            );
        }
        $this->assertEquals(
            count($oldOwnerPermissionIds),
            count($newOwnerPermissionIds)
        );

        // ensure the owner is updated
        $this->assertFalse($oldOwner->fresh()->is_prime_owner);
        $this->assertTrue($newOwner->fresh()->is_prime_owner);
    }
}
