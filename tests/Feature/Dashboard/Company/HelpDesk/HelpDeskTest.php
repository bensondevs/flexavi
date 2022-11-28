<?php

namespace Tests\Feature\Dashboard\Company\Employee;

use App\Models\User\User;
use Database\Factories\HelpDeskFactory;
use Database\Factories\OwnerFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\Company\HelpDesk\HelpDeskController
 *      To the tested controller class.
 */
class HelpDeskTest extends TestCase
{
    use WithFaker;

    /**
     * Test populate company help desks
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\HelpDesk\HelpDeskController::populate()
     *      To the tested controller method.
     */
    public function test_populate_help_desks(): void
    {
        $owner = OwnerFactory::new()->prime()->create();
        $user = $owner->user;

        $this->actingAs($user);

        $helpDesks = HelpDeskFactory::new()->for($user->owner->company)->count(2)->create();

        $response = $this->getJson(
            '/api/dashboard/companies/help_desks'
        );

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) use ($helpDesks) {
            $json->has('help_desks');
            $json->whereType('help_desks.data', 'array');

            for ($i=0; $i < count($helpDesks); $i++) {
                $json->has('help_desks.data.'.$i.'.id');
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

    /**
     * Test store HelpDesk
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\HelpDesk\HelpDeskController::store()
     *      To the tested controller method.
     */
    public function test_store_help_desk(): void
    {
        $owner = OwnerFactory::new()->prime()->create();
        $user = $owner->user;

        $this->actingAs($user);

        $company = $user->owner->company;

        $input = [
            'title' => $this->faker->title ,
            'content' => $this->faker->text ,
            'user_id' => (User::factory()->owner($company)->create())->id ,
        ];

        $response = $this->postJson('/api/dashboard/companies/help_desks/store', $input);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('help_desk');

            $json->has('help_desk.id');
            $json->has('help_desk.title');
            $json->has('help_desk.content');

            $json->has('status');
            $json->has('message');
        });
    }

    /**
     * Test view HelpDesk
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\HelpDesk\HelpDeskController::view()
     *      To the tested controller method.
     */
    public function test_view_help_desk(): void
    {
        $owner = OwnerFactory::new()->prime()->create();
        $user = $owner->user;

        $this->actingAs($user);

        $company = $user->owner->company;

        $helpDesk = HelpDeskFactory::new()
            ->for($company)
            ->for(User::factory()->owner($company)->create())
            ->create();

        $response = $this->getJson("/api/dashboard/companies/help_desks/view?id=$helpDesk->id&with_user=true");


        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('help_desk');

            $json->has('help_desk.id');
            $json->has('help_desk.title');
            $json->has('help_desk.content');
            $json->whereType('help_desk.user', 'array');
            $json->has('help_desk.user.id');
        });
    }

     /**
     * Test update HelpDesk
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\HelpDesk\HelpDeskController::update()
     *      To the tested controller method.
     */
    public function test_update_help_desk(): void
    {
        $owner = OwnerFactory::new()->prime()->create();
        $user = $owner->user;

        $this->actingAs($user);

        $company = $user->owner->company;

        $helpDesk = HelpDeskFactory::new()->for($company)->create();

        $input = [
            'id' => $helpDesk->id,
            'title' => $this->faker->title ,
            'content' => $this->faker->text ,
        ];

        $response = $this->putJson('/api/dashboard/companies/help_desks/update', $input);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status')
                ->has('message');
        });
    }

    /**
     * Test delete HelpDesk
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\HelpDesk\HelpDeskController::delete()
     *      To the tested controller method.
     */
    public function test_delete_help_desk(): void
    {
        $owner = OwnerFactory::new()->prime()->create();
        $user = $owner->user;

        $this->actingAs($user);

        $company = $user->owner->company;

        $helpDesk = HelpDeskFactory::new()->for($company)->create();

        $response = $this->deleteJson("/api/dashboard/companies/help_desks/delete?id=$helpDesk->id");

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
        });
    }
}
