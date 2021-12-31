<?php

namespace Tests\Feature\Dashboard\Companies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ 
    User,
    Company, 
    Owner, 
    PostIt, 
    Employee,
    PostItAssignedUser 
};

class PostItTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Module base url for test
     * 
     * @var string
     */
    private $baseUrl = '/api/dashboard/companies/post_its';

    /**
     * Populate post its of company test.
     *
     * @return void
     */
    public function test_view_all_post_its()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('post_its');
        });
    }

    /**
     * Store post it of company test.
     *
     * @return void
     */
    public function test_store_post_it()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl . '/store';
        $response = $this->json('POST', $url, [
            'user_id' => $user->id,
            'content' => 'Lorem ipsum dolor sit amet',
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Update post it of company test.
     *
     * @return void
     */
    public function test_update_post_it()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl . '/update';
        $postIt = PostIt::factory()
            ->for($company)
            ->for($user)
            ->create();
        $response = $this->json('PATCH', $url, [
            'post_it_id' => $postIt->id,
            'content' => 'Edited content',
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Assign post it user test.
     *
     * @return void
     */
    public function test_assign_post_it_user()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl . '/assign_user';
        $postIt = PostIt::factory()
            ->for($company)
            ->for($user)
            ->create();
        $employeeUser = User::factory()->create();
        $employee = Employee::factory()
            ->for($company)
            ->for($employeeUser)
            ->create();
        $response = $this->json('POST', $url, [
            'post_it_id' => $postIt->id,
            'assigned_user_id' => $employeeUser->id,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Unassign post it user test.
     *
     * @return void
     */
    public function test_unassign_post_it_user()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl . '/assign_user';
        $postIt = PostIt::factory()
            ->for($company)
            ->for($user)
            ->create();
        $employee = Employee::factory()->for($company)->create();
        $assignedUser = $employee->user;
        $pivot = PostItAssignedUser::factory()
            ->for($postIt)
            ->for($assignedUser)
            ->create();
        $response = $this->json('POST', $url, [
            'post_it_id' => $postIt->id,
            'assigned_user_id' => $assignedUser->id,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Delete post it test.
     *
     * @return void
     */
    public function test_delete_post_it()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl . '/delete';
        $postIt = PostIt::factory()
            ->for($company)
            ->for($user)
            ->create();
        $response = $this->json('DELETE', $url, ['post_it_id' => $postIt->id]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }
}
