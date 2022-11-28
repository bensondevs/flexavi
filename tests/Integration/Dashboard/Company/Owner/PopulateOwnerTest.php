<?php

namespace Tests\Integration\Dashboard\Company\Owner;

use App\Models\User\User;
use App\Traits\FeatureTestUsables;
use Database\Factories\OwnerFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 *  @see App\Http\Controllers\Api\Company\Owner\OwnerController::companyOwners()
 *      to the tested controller method
 */
class PopulateOwnerTest extends TestCase
{
    use WithFaker;
    use FeatureTestUsables;

    /**
     * Module base URL container constant.
     *
     * @const
     */
    public const MODULE_BASE_URL = '/api/dashboard/companies/owners';

    /**
    * Test populate company owners by search keyword
    *
    * @return void
    */
    public function test_populate_company_owners_by_search_keyword(): void
    {
        $user = $this->authenticateAsOwner();
        $company = $user->owner->company;

        $keyword = "Jack Dorsey";
        $matchedOwners = OwnerFactory::new()
            ->for($company)
            ->for(UserFactory::new()->withoutRole()->create([
                    'fullname' => $keyword,
                    'email' => str_snake_case($keyword) . '_' . $this->faker->email,
                ]))
            ->count(2)
            ->create();
        $unmatchOwners = OwnerFactory::new()
            ->for($company)
            ->for(UserFactory::new()->withoutRole()->create())
            ->count(2)
            ->create();

        $response = $this->getJson(
            self::MODULE_BASE_URL . "?with_user=true&with_address=true&keyword=$keyword"
        );

        $this->assertResponseAttributeIsPaginationInstance($response, 'owners');

        $content = $response->getOriginalContent();
        $owners = $content['owners'];
        $this->assertTrue($matchedOwners->count() == $owners->count());
        for ($i=0; $i < count($owners) ; $i++) {
            $owner = $owners[$i];

            $this->assertEquals($owner->id, $matchedOwners[$i]->id);
            $this->assertArrayHasKey('user', $owner);
            $this->assertEquals($owner->user->fullname, $keyword);
        }
    }

    /**
    * Test populate main owner of company (the main owner of the company should have an identifier)
    *
    * @return void
    */
    public function test_populate_company_owners_and_the_main_owner_should_have_identifier(): void
    {
        $mainOwner = OwnerFactory::new()->prime()->create();
        $this->assertTrue($mainOwner->is_prime_owner, true);

        $this->actingAs($mainOwner->user);

        $response = $this->getJson(self::MODULE_BASE_URL);

        $this->assertResponseAttributeIsPaginationInstance($response, 'owners');

        $content = $response->getOriginalContent();
        $owners = $content['owners'];

        $mainOwnerTotal = 0;

        foreach ($owners as $owner) {
            if ($owner->is_prime_owner) {
                $mainOwnerTotal++ ;
            }
        }

        $this->assertEquals(1, $mainOwnerTotal);
    }
}
