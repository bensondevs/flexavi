<?php

namespace Tests\Integration\Dashboard\Company\Notificaation;

use Database\Factories\HelpDeskFactory;
use Database\Factories\OwnerFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 *  @see App\Http\Controllers\Api\Company\HelpDesk\HelpDeskController::populate()
 *      to the tested controller
 */
class HelpDesk_PopulateTest extends TestCase
{
    use WithFaker;

    /**
     * Base API URL of the module.
     *
     * @const
     */
    public const BASE_API_URL = '/api/dashboard/companies/help_desks';

    /**
    * Test populate help desks by search keyword
    *
    * @return void
    */
    public function test_populate_help_desks_by_search_keyword(): void
    {
        $owner = OwnerFactory::new()->prime()->create();
        $user = $owner->user;

        $this->actingAs($user);

        $keyword = "What is dashboard menu";

        $matchedHelpDesks = HelpDeskFactory::new()->for($user->owner->company)->count(2)->create([
            'title' => $keyword,
            'content' => $keyword .' ' .$this->faker->text,
        ]);
        $unmatchHelpDesks = HelpDeskFactory::new()->for($user->owner->company)->count(2)->create();

        $response = $this->getJson(self::BASE_API_URL . "?keyword=$keyword");

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) use ($keyword, $matchedHelpDesks) {
            $json->has('help_desks');
            $json->whereType('help_desks.data', 'array');

            for ($i=0; $i < $matchedHelpDesks->count(); $i++) {
                $json->where("help_desks.data.$i.title", $keyword);
            }

            // pagination meta
            $json->has('help_desks.current_page');
            $json->has('help_desks.first_page_url');
            $json->has('help_desks.from');
            $json->has('help_desks.last_page');
            $json->has('help_desks.last_page_url');
            $json->has('help_desks.links');
            $json->has('help_desks.next_page_url');
            $json->has('help_desks.path');
            $json->has('help_desks.per_page');
            $json->has('help_desks.prev_page_url');
            $json->has('help_desks.to');
            $json->has('help_desks.total');
        });
    }
}
