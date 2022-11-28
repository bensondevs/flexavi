<?php

namespace Tests\Feature\Dashboard\Company\Owner;

use App\Http\Resources\Owner\OwnerResource;
use App\Models\Owner\Owner;
use App\Models\User\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class OwnerTest extends TestCase
{
    use WithFaker;

    /**
    * Base API URL of the module.
    *
    * @const
    */
    public const BASE_API_URL = '/api/dashboard/companies/owners';

    /**
    * Authenticate the tester user to access the endpoint.
    *
    * @test
    * @return User
    */
    private function authenticate(): User
    {
        $user = User::factory()->owner()->create();
        $this->actingAs($user);

        return $user;
    }

    /**
     * Assert populate request succeed.
     *
     * @test
     * @param TestResponse $response
     * @return void
     */
    private function assertPopulateRequestSucceed(TestResponse $response): void
    {
        // Assert response status is 200
        $response->assertOk();

        // Assert response content is as expected.
        $response->assertJson(function (AssertableJson $json) {
            $json->has('owners');
            $json->whereType('owners.data', 'array');

            // pagination meta attributes
            $paginationMetaAttributes = [
                'current_page',
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'links',
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total',
            ];
            foreach ($paginationMetaAttributes as $paginationMetaAttribute) {
                $json->has('owners.' . $paginationMetaAttribute);
            }
        });
    }

    /**
     * Assert returned response has Owner resource instance.
     *
     * @test
     * @param TestResponse $response
     * @return void
     */
    private function assertResponseHasOwnerResource(TestResponse $response): void
    {
        $content = $response->getOriginalContent();
        $this->assertArrayHasKey('owner', $content);

        $owner = $content['owner'];
        $this->assertInstanceOf(OwnerResource::class, $owner);
    }

     /**
     * Assert response has success attributes.
     *
     * @test
     * @param TestResponse $response
     * @return void
     */
    private function assertResponseHasSuccessAttributes(TestResponse $response): void
    {
        $content = $response->getOriginalContent();

        $this->assertArrayHasKey('status', $content);
        $this->assertArrayHasKey('message', $content);

        $this->assertEquals('success', $content['status']);
    }


    /**
     * Test populate company owners
     *
     * @return void
     */
    public function test_populate_company_owners(): void
    {
        $user = $this->authenticate();
        $response = $this->getJson(self::BASE_API_URL);
        $response->assertStatus(200);
        $this->assertPopulateRequestSucceed($response);
    }

    /**
     * Test populate company trashed owners
     *
     * @return void
     */
    public function test_populate_company_trashed_owners(): void
    {
        $user = $this->authenticate();
        $response = $this->getJson(self::BASE_API_URL . '/trasheds');
        $response->assertStatus(200);
        $this->assertPopulateRequestSucceed($response);
    }

    /**
     * Test get company owner
     *
     * @return void
     */
    public function test_get_company_owner(): void
    {
        $user = $this->authenticate();
        $owner = Owner::factory()->for($user->owner->company)->create();
        $response = $this->getJson(
            self::BASE_API_URL . '/view' .
            "?id=$owner->id" .
            '&with_user=true' .
            '&with_user.permissions=true'
        );
        $response->assertStatus(200);
        $this->assertResponseHasOwnerResource($response);
    }

    /**
     * Test get set image
     *
     * @return void
     */
    public function test_set_image_company_owner(): void
    {
        $user = $this->authenticate();
        $owner = Owner::factory()->for($user->owner->company)->create();
        $file = UploadedFile::fake()->image('avatar.jpg');
        $response = $this->postJson(self::BASE_API_URL . '/set_image', [
            'owner_id' => $owner->id,
            'image' => $file
        ]);
        $response->assertStatus(201);
        $this->assertResponseHasSuccessAttributes($response);
    }

    /**
     * Test delete company owner
     *
     * @return void
     */
    public function test_delete_company_owner(): void
    {
        $user = $this->authenticate();

        $owner = Owner::factory()->for($user->owner->company)->notPrime()->create();
        $response = $this->deleteJson(self::BASE_API_URL . '/delete', [
            'id' => $owner->id
        ]);
        $response->assertStatus(200);
        $this->assertResponseHasSuccessAttributes($response);
    }

    /**
     * Test delete company owner permanently
     *
     * @return void
     */
    public function test_delete_company_owner_permanently(): void
    {
        $user = $this->authenticate();

        $owner = Owner::factory()->for($user->owner->company)->notPrime()->create();
        $response = $this->deleteJson(self::BASE_API_URL . '/delete', [
            'id' => $owner->id,
            'force' => true
        ]);
        $response->assertStatus(200);
        $this->assertResponseHasSuccessAttributes($response);
    }

    /**
     * Test make as main owner
     *
     * @return void
     */
    public function test_make_as_main_owner(): void
    {
        // @see Tests\Integration\Dashboard\Company\Owner\MakeAsMainOwnerTest
        // for the integration test
    }

    /**
     * Test restore company owner
     *
     * @return void
     */
    public function test_restore_company_owner(): void
    {
        $user = $this->authenticate();

        $owner = Owner::factory()->for($user->owner->company)->notPrime()->create();
        $owner->delete();

        $response = $this->patchJson(self::BASE_API_URL  . '/restore', [
            'id' => $owner->id
        ]);
        $response->assertStatus(200);
        $this->assertResponseHasOwnerResource($response);
        $this->assertResponseHasSuccessAttributes($response);
    }
}
