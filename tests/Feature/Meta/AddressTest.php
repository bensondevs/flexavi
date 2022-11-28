<?php

namespace Tests\Feature\Meta;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AddressTest extends TestCase
{
    /**
     * Module base URL container constant.
     *
     * @const
     */
    public const MODULE_BASE_URL = '/api/meta/address';

    /**
     * Test get all address types
     *
     * @return void
     * @see \App\Http\Controllers\Meta\AddressController::allAddressTypes()
     *      to tested controller method
     */
    public function test_get_all_address_types(): void
    {
        $response = $this->getJson(self::MODULE_BASE_URL . '/all_address_types');
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('1', 'Visiting Address');
            $json->where('2', 'Invoicing Address');
            $json->where('3', 'Other');
        });
    }

    /**
     * Test address autocomplete by providing zipcode and house number
     *
     * @return void
     */
    public function test_address_autocomplete()
    {
        // this is a valid zipcode and house number so don't change it
        $zipcode = '5212GG';
        $houseNumber = 28;

        $response = $this->get(self::MODULE_BASE_URL . "/autocomplete?zipcode=$zipcode&house_number=$houseNumber");

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            /* Response Example
                {
                    +"address": {#3768
                        +"constructionYear": 1947
                        +"country": "Netherlands"
                        +"countryCode": "NL"
                        +"lat": 51.70036
                        +"lng": 5.30725
                        +"municipality": "'s-Hertogenbosch"
                        +"postalCode": "5212GG"
                        +"province": "Noord-Brabant"
                        +"purposes": array:1 [
                                0 => "woonfunctie"
                            ]
                        +"settlement": "'s-Hertogenbosch"
                        +"street": "Pieter Borstraat"
                        +"streetNumber": 28
                        +"surfaceArea": 128
                    }
                }
            */

            $json->has('address.constructionYear');
            $json->has('address.country');
            $json->has('address.countryCode');
            $json->has('address.lat');
            $json->has('address.lng');
            $json->has('address.municipality');
            $json->has('address.postalCode');
            $json->has('address.province');
            $json->has('address.purposes');
            $json->has('address.settlement');
            $json->has('address.street');
            $json->has('address.streetNumber');
            $json->has('address.surfaceArea');
        });
    }
}
