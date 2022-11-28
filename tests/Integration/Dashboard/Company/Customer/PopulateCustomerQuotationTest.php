<?php

namespace Tests\Feature\Dashboard\Company\Customer;

use App\Traits\FeatureTestUsables;
use Database\Factories\CustomerFactory;
use Database\Factories\QuotationFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\Company\Quotation\QuotationController::customerQuotations()
 *      To the tested controller class.
 */
class PopulateCustomerQuotationTest extends TestCase
{
    use WithFaker;
    use FeatureTestUsables;

    /**
     * Module base URL container constant.
     *
     * @const
     */
    public const MODULE_BASE_URL = '/api/dashboard/companies/customers/quotations';

    /**
     * Test populate customer quotations and paginate to page 2
     *
     * @return void
     */
    public function test_populate_customer_quotations_and_paginate_to_next_page(): void
    {
        $user = $this->authenticateAsOwner();
        $company = $user->owner->company;

        $customer = CustomerFactory::new()->for($company)->create();
        QuotationFactory::new()->for($company)->for($customer)->count(20)->create();

        $this->assertResponseAttributeIsPaginationInstance(
            $this->getJson(self::MODULE_BASE_URL."?customer_id=$customer->id")->assertOk(),
            'quotations',
            [
                'data.0.id',
                'data.9.id',
            ]
        );

        $this->assertResponseAttributeIsPaginationInstance(
            $this->getJson(self::MODULE_BASE_URL."?customer_id=$customer->id&page=2")->assertOk(),
            'quotations',
            [
                'data.0.id',
                'data.9.id',
            ]
        );
    }

    /**
     * Test populate customer quotations by search keyword
     *
     * @return void
     */
    public function test_populate_customer_quotations_by_search_keyword(): void
    {
        $user = $this->authenticateAsOwner();
        $company = $user->owner->company;


        $keyword = randomString(16);
        $customer = CustomerFactory::new()->for($company)->create();
        $matchedQuotation = QuotationFactory::new()->for($company)->for($customer)
            ->create(['number' => $keyword]);
        $unmatchedQuotation = QuotationFactory::new()->for($company)->for($customer)
            ->create();

        $response = $this->getJson(self::MODULE_BASE_URL."?customer_id=$customer->id&keyword=$keyword")
            ->assertOk();

        $this->assertResponseAttributeIsPaginationInstance(
            $response,
            'quotations',
            requiredAttributes: [
                'data.0.id' => $matchedQuotation->id
            ],
            whereNotAttributes: [
                'data.0.id' => $unmatchedQuotation->id,
            ]
        );
    }
}
