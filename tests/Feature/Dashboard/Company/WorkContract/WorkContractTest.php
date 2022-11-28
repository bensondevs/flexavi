<?php

namespace Tests\Feature\Dashboard\Company\WorkContract;

use App\Enums\WorkContract\WorkContractStatus;
use App\Models\Customer\Customer;
use App\Models\Setting\WorkContractSetting;
use App\Models\User\User;
use App\Models\WorkContract\WorkContract;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class WorkContractTest extends TestCase
{
    use WithFaker;

    /**
     * Test populate company work contracts
     *
     * @return void
     */
    public function test_populate_company_work_contracts(): void
    {
        $this->actingAs(
            $user = User::factory()->owner()->create()
        );
        WorkContract::factory()->for($user->owner->company)->create(['id' => generateUuid()]);

        $response = $this->getJson("/api/dashboard/companies/work_contracts");

        $response->assertOk();

        $response->assertJson(function (AssertableJson $json) {
            $json->has('work_contracts');
            $json->whereType('work_contracts.data', 'array');

            // pagination meta
            $json->has('work_contracts.current_page');
            $json->has('work_contracts.first_page_url');
            $json->has('work_contracts.from');
            $json->has('work_contracts.last_page');
            $json->has('work_contracts.last_page_url');
            $json->has('work_contracts.links');
            $json->has('work_contracts.next_page_url');
            $json->has('work_contracts.path');
            $json->has('work_contracts.per_page');
            $json->has('work_contracts.prev_page_url');
            $json->has('work_contracts.to');
            $json->has('work_contracts.total');
        });
    }

    /**
     * Test draft / save  work contract
     *
     * @return void
     */
    public function test_draft_work_contract(): void
    {
        $this->actingAs(
            $user = User::factory()->owner()->create()
        );

        $company = $user->company;
        WorkContractSetting::factory()->for($company)->create();
        $customer = Customer::factory()->for($company)->create();


        $input = [
            'customer_id' => $customer->id,
            'footer' => $this->faker->paragraph,
            'number' => $this->faker->unique()->numberBetween(1, 100),
        ];

        $response = $this->postJson('/api/dashboard/companies/work_contracts/draft', $input);

        $response->assertCreated();

        $response->assertJsonStructure([
            "status", "message"
        ]);
    }

    /**
     * Test draft / save  work contract
     *
     * @return void
     */
    public function test_edit_draft_work_contract(): void
    {
        $this->actingAs(
            $user = User::factory()->owner()->create()
        );

        $company = $user->company;
        $customer = Customer::factory()->for($company)->create();

        $workContract = WorkContract::factory()->for($company)->create([
            'customer_id' => $customer->id,
            'status' => WorkContractStatus::Drafted,
            'number' => $this->faker->unique()->numberBetween(1, 100),
        ]);

        $input = [
            'work_contract_id' => $workContract->id,
            'customer_id' => $customer->id,
            'footer' => $this->faker->paragraph,
            'number' => $this->faker->unique()->numberBetween(1, 100),
        ];

        $response = $this->postJson('/api/dashboard/companies/work_contracts/draft', $input);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            "status", "message"
        ]);
    }

    /**
     * Test view work contract
     *
     * @return void
     */
    public function test_view_work_contract(): void
    {
        $this->actingAs(
            $user = User::factory()->owner()->create()
        );

        $company = $user->company;
        $customer = Customer::factory()->for($company)->create();

        $workContract = WorkContract::factory()
            ->for($company)
            ->for($customer)
            ->create();

        $response = $this->getJson("/api/dashboard/companies/work_contracts/view?work_contract_id={$workContract->id}");
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "work_contract" => [
                "id",
            ]
        ]);
    }

    /**
     * Test delete work contract
     *
     * @return void
     */
    public function test_restore_work_contract(): void
    {
        $this->actingAs(
            $user = User::factory()->owner()->create()
        );

        $company = $user->company;
        $customer = Customer::factory()->for($company)->create();

        $workContract = WorkContract::factory()
            ->for($company)
            ->for($customer)
            ->create();

        //  soft delete the created work contract
        $workContract->delete();

        //  make sure it is soft deleted
        $this->assertSoftDeleted((new WorkContract())->getTable(), [
            "id" => $workContract->id
        ]);

        $response = $this->patchJson("/api/dashboard/companies/work_contracts/restore", [
            'work_contract_id' => $workContract->id
        ]);

        //  make sure work contract is restored
        $this->assertDatabaseHas((new WorkContract())->getTable(), [
            "id" => $workContract->id,
            "deleted_at" => null,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "status", "message"
        ]);
    }

    /**
     * Test delete work contract
     *
     * @return void
     */
    public function test_delete_work_contract(): void
    {
        $this->actingAs(
            $user = User::factory()->owner()->create()
        );

        $company = $user->owner->company;
        $customer = Customer::factory()->for($company)->create();

        $workContract = WorkContract::factory()
            ->for($company)
            ->for($customer)
            ->drafted()
            ->create();

        $response = $this->deleteJson("/api/dashboard/companies/work_contracts/delete", [
            'work_contract_id' => $workContract->id
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            "status", "message"
        ]);
    }

    /**
     * Test force_delete work contract
     *
     * @return void
     */
    public function test_force_delete_work_contract(): void
    {
        $this->actingAs(
            $user = User::factory()->owner()->create()
        );

        $company = $user->company;
        $customer = Customer::factory()->for($company)->create();

        $workContract = WorkContract::factory()
            ->for($company)
            ->for($customer)
            ->create();

        $response = $this->deleteJson("/api/dashboard/companies/work_contracts/delete", [
            'work_contract_id' => $workContract->id,
            'force_delete' => true
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            "status", "message"
        ]);
    }

    /**
     * Test upload work contract signed document
     *
     * @return void
     */
    public function test_upload_work_contract_signed_document(): void
    {
        $this->actingAs(
            $user = User::factory()->owner()->create()
        );

        $company = $user->owner->company;
        $customer = Customer::factory()->for($company)->create();

        $workContract = WorkContract::factory()
            ->for($company)
            ->for($customer)
            ->signed()
            ->create();

        $response = $this->postJson('/api/dashboard/companies/work_contracts/signed_documents/upload', [
            'work_contract_id' => $workContract->id,
            'signed_document' => UploadedFile::fake()->create('document.pdf', 1000),
        ]);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'status', 'message'
        ]);

        $this->assertDatabaseHas((new WorkContract())->getTable(), [
            'id' => $workContract->id,
            'status' => WorkContractStatus::Signed,
        ]);
    }

    /**
     * Test remove work contract signed document
     *
     * @return void
     */
    public function test_remove_work_contract_signed_document(): void
    {
        $this->actingAs(
            $user = User::factory()->owner()->create()
        );

        $company = $user->owner->company;
        $customer = Customer::factory()->for($company)->create();

        $workContract = WorkContract::factory()
            ->for($company)
            ->for($customer)
            ->signed()
            ->create();

        $response = $this->deleteJson('/api/dashboard/companies/work_contracts/signed_documents/remove', [
            'work_contract_id' => $workContract->id,
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'status', 'message'
        ]);

        $this->assertDatabaseHas((new WorkContract())->getTable(), [
            'id' => $workContract->id,
            'status' => WorkContractStatus::Signed,
        ]);
    }
}
