<?php

namespace Tests\Feature\Dashboard\Company\Meta;

use App\Models\User\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\Company\Meta\AddressMetaController
 *      To see the controller
 */
class AddressMetaTest extends TestCase
{
    /**
     * Test populate autocomplete address
     * @return void
     * @see \App\Http\Controllers\Api\Company\Meta\AddressMetaController::autocomplete()
     *      To see the method
     */
    public function test_autocomplete_address_with_valid_params(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $response = $this->getJson(urlWithParams('/api/dashboard/company/meta/addresses/autocomplete', [
            'zipcode' => '1000AE',
            'house_number' => '10',
        ]));

        $response->assertOk();

        $response->assertJson(function (AssertableJson $json) {
            $json->has('address');
            $json->has('address.street');
            $json->has('address.house_number');
            $json->has('address.house_number_addition');
            $json->has('address.city');
            $json->has('address.zipcode');
            $json->has('address.province');
            $json->has('address.country');
        });
    }

    /**
     * Test populate autocomplete address
     * @return void
     * @see \App\Http\Controllers\Api\Company\Meta\AddressMetaController::autocomplete()
     *      To see the method
     */
    public function test_autocomplete_address_with_invalid_params(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $response = $this->getJson(urlWithParams('/api/dashboard/company/meta/addresses/autocomplete', [
            'zipcode' => 'WRONG-ZIPCODE',
            'house_number' => 'WRONG-HOUSE-NUMBER',
        ]));

        $response->assertOk();
    }
}
