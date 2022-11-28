<?php

namespace Tests\Feature\Dashboard\Company\Customer;

use App\Traits\FeatureTestUsables;
use Database\Factories\AddressFactory;
use Database\Factories\CustomerFactory;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\Company\Customer\CustomerController::trashedCustomers()
 *      To the tested controller class.
 */
class PopulateTrashedCustomerTest extends TestCase
{
    use WithFaker;
    use FeatureTestUsables;

    /**
     * Module base URL container constant.
     *
     * @const
     */
    public const MODULE_BASE_URL = '/api/dashboard/companies/customers/trasheds';

    /**
     * Test populate trashed customers based on cities
     *
     * @return void
     */
    public function test_populate_trashed_customers_based_on_cities(): void
    {
        $user = $this->authenticateAsOwner();

        $cities = ['Tokyo', 'Seoul'];

        // prepare two customers with provided cities
        foreach ($cities as $city) {
            $customer = CustomerFactory::new()
            ->for($user->owner->company)
            ->create(['deleted_at' => now()]);
            $address = AddressFactory::new()->customer($customer)->create(['city' => $city]);
        }

        // create one customer that not matched with provided city
        $notMatchedCustomer = CustomerFactory::new()
            ->for($user->owner->company)
            ->create(['deleted_at' => now()]);
        AddressFactory::new()->customer($notMatchedCustomer)->create(['city' => randomString(20)]);

        $response = $this->getJson(self::MODULE_BASE_URL . '?cities=' . implode(',', $cities))
            ->assertOk();

        $content = $response->getOriginalContent();
        $customers = $content['customers'];

        foreach ($customers as $customer) {
            // assert the returned customer is not equal to the notMatchedCustomer's address
            $this->assertNotEquals($notMatchedCustomer->id, $customer->id);
        }
    }

    /**
     * Test populate trashed customers by search keyword
     *
     * @return void
     */
    public function test_populate_trashed_customers_by_search_keyword(): void
    {
        $user = $this->authenticateAsOwner();

        $keyword = "Hello world";

        // create two customers that matched with search keyword
        for ($i = 1; $i <= 2; $i++) {
            $customer = CustomerFactory::new()
                ->for($user->owner->company)
                ->create(['deleted_at' => now()]);
            switch ($i) {
                case 1:
                    $customer->fullname = randomString() . " $keyword";
                    break;
                case 2:
                    $customer->email = randomString() . " $keyword";
                    break;
            }
            $customer->save();
        }

        // create one customer that not matched with search keyword
        $notMatchedCustomer = CustomerFactory::new()
            ->for($user->owner->company)
            ->create(['deleted_at' => now()]);

        $response = $this->getJson(self::MODULE_BASE_URL . "?keyword=$keyword")
            ->assertOk();

        $content = $response->getOriginalContent();
        $customers = $content['customers'];

        foreach ($customers as $customer) {
            $keys = ['fullname', 'email'];

            $isMacthed = false;
            foreach ($keys as $key) {
                if ($isMacthed) {
                    return;
                }

                // check if  customer's property is matched with the search keyword
                if (
                    Str::contains($customer->{$key}, $keyword)
                ) {
                    $isMacthed = true;
                };
            }

            // assert that the returned customer from response matches the expected search keyword
            $this->assertTrue($isMacthed);
        }
    }
}
