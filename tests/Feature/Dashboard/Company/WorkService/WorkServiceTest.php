<?php

namespace Tests\Feature\Dashboard\Company\WorkService;

use App\Enums\WorkService\WorkServiceStatus;
use App\Models\User\User;
use App\Models\WorkService\WorkService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class WorkServiceTest extends TestCase
{
    use WithFaker;

    /**
     * Module base URL container constant.
     *
     * @const
     */
    const MODULE_BASE_URL = '/api/dashboard/companies/work_services';

    /**
     * Test populate work services.
     *
     * @return void
     */
    public function test_populate_company_work_services(): void
    {
        $user = $this->authenticate();

        $workServices = WorkService::factory()->count(5)->create([
            'company_id' => $user->owner->company->id,
        ]);

        $response = $this->getJson(self::MODULE_BASE_URL);

        $this->assertPopulateRequestSucceed($response);
    }

    /**
     * Authenticate the tester user to access the endpoint.
     *
     * @return User
     */
    private function authenticate(): User
    {
        $user = User::factory()->owner()->create();
        $user->refresh()->load(['owner.company']);

        $this->actingAs($user);

        return $user;
    }

    /**
     * Assert populate request succeed.
     *
     * @param TestResponse $response
     * @return void
     */
    private function assertPopulateRequestSucceed(TestResponse $response): void
    {
        // Assert response status is 200
        $response->assertOk();

        // Assert response content is as expected.
        $response->assertJson(function (AssertableJson $json) {
            $json->has('work_services');
            $json->whereType('work_services.data', 'array');

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
                $json->has('work_services.' . $paginationMetaAttribute);
            }
        });
    }

    /**
     * Test populate trashed work services.
     *
     * @return void
     */
    public function test_populate_trashed_company_work_services(): void
    {
        $user = $this->authenticate();

        $response = $this->getJson(self::MODULE_BASE_URL . '/trasheds');

        $this->assertPopulateRequestSucceed($response);
    }

    /**
     * Test store work service.
     *
     * @return void
     */
    public function test_store_work_service(): void
    {
        $user = $this->authenticate();

        $data = [
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'price' => rand(100, 1000),
            'tax_percentage' => rand(5, 10),
            'status' => WorkServiceStatus::getRandomValue(),
            'unit' => 'm2',
        ];

        $response = $this->postJson(self::MODULE_BASE_URL . '/store', $data);
        $response->assertCreated();
        $response->assertJson(function (AssertableJson $json) use ($data) {
            $json->where('status', 'success');
            $json->has('message');
            $json->has('work_service');
            $json->has('work_service.id');
        });

        $this->assertDatabaseHas((new WorkService())->getTable(), $data);
    }

    /**
     * Test view work service.
     *
     * @return void
     */
    public function test_view_work_service(): void
    {
        $user = $this->authenticate();
        $company = $user->owner->company;
        $workService = WorkService::factory()->for($company)->create();
        $response = $this->getJson(urlWithParams(self::MODULE_BASE_URL . '/view', [
            'work_service_id' => $workService->id
        ]));

        $response->assertOk();

        $response->assertJson(function (AssertableJson $json) use ($workService) {
            $json->has('work_service');
            $json->where('work_service.id', $workService->id);
        });
    }

    /**
     * Test change status work service.
     *
     * @return void
     */
    public function test_change_status_work_service(): void
    {
        $user = $this->authenticate();
        $company = $user->owner->company;
        $workService = WorkService::factory()->for($company)->create();

        $oldStatus = $workService->status;

        $response = $this->patchJson('/api/dashboard/companies/work_services/change_status', [
            'work_service_id' => $workService->id
        ]);

        $response->assertOk();

        $response->assertJson(function (AssertableJson $json) use ($workService) {
            $json->where('status', 'success');
            $json->has('message');
            $json->has('work_service');
            $json->has('work_service.id');
        });

        $this->assertDatabaseHas((new WorkService())->getTable(), [
            'id' => $workService->id,
            'status' => $oldStatus === WorkServiceStatus::Active ? WorkServiceStatus::Inactive : WorkServiceStatus::Active,
        ]);
    }


    /**
     *  Test update work service.
     *
     * @return void
     */
    public function test_update_work_service(): void
    {
        $user = $this->authenticate();

        $company = $user->owner->company;

        $workService = WorkService::factory()->for($company)->create();

        $data = [
            'work_service_id' => $workService->id,
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'price' => rand(100, 1000),
            'tax_percentage' => rand(5, 10),
            'status' => WorkServiceStatus::getRandomValue(),
            'unit' => 'm2',
        ];


        $response = $this->putJson(self::MODULE_BASE_URL . '/update', $data);
        $response->assertOk();

        $response->assertJson(function (AssertableJson $json) use ($data) {
            $json->where('status', 'success');
            $json->has('message');
            $json->has('work_service');
            $json->has('work_service.id');
        });

        unset($data['work_service_id']);
        $data['id'] = $workService->id;
        $this->assertDatabaseHas((new WorkService())->getTable(), $data);
    }

    /**
     * Test delete work service.
     *
     * @return void
     */
    public function test_delete_work_service(): void
    {
        $user = $this->authenticate();

        $company = $user->owner->company;

        $workService = WorkService::factory()->for($company)->inactive()->create();


        $response = $this->deleteJson(self::MODULE_BASE_URL . '/delete', [
            'work_service_id' => $workService->id
        ]);

        $response->assertOk();

        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
        });

        $this->assertSoftDeleted((new WorkService())->getTable(), [
            'id' => $workService->id
        ]);
    }

    /**
     * Test delete work service.
     *
     * @return void
     */
    public function test_permanently_delete_invoice(): void
    {
        $user = $this->authenticate();
        $company = $user->owner->company;

        $workService = WorkService::factory()->for($company)->inactive()->create();


        $response = $this->deleteJson(self::MODULE_BASE_URL . '/delete', [
            'work_service_id' => $workService->id,
            'force' => true
        ]);

        $response->assertOk();

        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
        });

        $this->assertDatabaseMissing((new WorkService())->getTable(), [
            'id' => $workService->id
        ]);
    }

    /**
     * Test restore work service.
     *
     * @return void
     */
    public function test_restore_work_service(): void
    {
        $user = $this->authenticate();
        $company = $user->owner->company;

        $workService = WorkService::factory()->for($company)->softDeleted()->create();


        $response = $this->patchJson(self::MODULE_BASE_URL . '/restore', [
            'work_service_id' => $workService->id,
        ]);

        $response->assertOk();

        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
            $json->has('work_service');
            $json->has('work_service.id');
        });

        $this->assertDatabaseHas((new WorkService())->getTable(), [
            'id' => $workService->id,
            'deleted_at' => null
        ]);
    }
}
