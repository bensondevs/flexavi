<?php

namespace Tests\Feature\Dashboard\Company\Customer;

use App\Http\Resources\Customer\CustomerNoteResource;
use App\Models\Customer\Customer;
use App\Models\Customer\CustomerNote;
use App\Models\User\User;
use App\Traits\FeatureTestUsables;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CustomerNoteTest extends TestCase
{
    use WithFaker, FeatureTestUsables;

    /**
     * Module base URL.
     *
     * @const
     */
    const MODULE_BASE_URL = '/api/dashboard/companies/customers/notes';

    /**
     * Test populate company customer_notes
     *
     * @return void
     */
    public function test_populate_customer_notes(): void
    {
        $this->authenticateAsOwner();
        $company = $this->getTestCompany();

        $customer = Customer::factory()
            ->for($company)
            ->create();
        $customerNotes = CustomerNote::factory()
            ->for($customer)
            ->count(10)
            ->create();

        $url = urlWithParams(self::MODULE_BASE_URL, [
            'customer_id' => $customer->id,
        ]);
        $response = $this->getJson($url);
        $response->assertOk();
        $this->assertResponseAttributeIsPaginationInstance(
            $response,
            'customer_notes',
        );
    }

    /**
     * Test populate company trashed customers
     *
     * @return void
     */
    public function test_populate_trashed_customer_notes(): void
    {
        $user = $this->authenticateAsOwner();

        $customer = Customer::factory()->for($user->owner->company)->create();
        $customerNotes = CustomerNote::factory()
            ->for($customer)
            ->count(10)
            ->softDeleted()
            ->create();

        $url = urlWithParams(self::MODULE_BASE_URL . '/trasheds', [
            'customer_id' => $customer->id,
        ]);
        $response = $this->getJson($url);
        $response->assertOk();
        $this->assertResponseAttributeIsPaginationInstance(
            $response,
            'customer_notes',
        );
    }

    /**
     * Test store a customer note
     *
     * @return void
     */
    public function test_store_customer_note(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $customer = Customer::factory()->create();

        $data = [
            'customer_id' => $customer->id,
            'note' => $this->faker->word
        ];
        $response = $this->postJson(self::MODULE_BASE_URL . '/store', $data);

        $response->assertStatus(201);

        $response->assertJson(function (AssertableJson $json) {
            $json->has('customer_note');
            $json->has('customer_note.id');

            // status meta
            $json->where('status', 'success');
            $json->has('message');
        });

        $this->assertDatabaseHas((new CustomerNote())->getTable(), [
            'customer_id' => $data['customer_id'],
            'note' => $data['note']
        ]);
    }

    /**
     * Test update a customer note
     *
     * @return void
     */
    public function test_update_customer_note(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $customer = Customer::factory()->create();

        $customerNote = CustomerNote::factory()->for($customer)->create();

        $data = [
            'id' => $customerNote->id,
            'customer_id' => $customer->id,
            'note' => $this->faker->word
        ];
        $response = $this->putJson(self::MODULE_BASE_URL . '/update', $data);

        $response->assertStatus(200);

        $response->assertJson(function (AssertableJson $json) {
            $json->has('customer_note');
            $json->has('customer_note.id');

            // status meta
            $json->where('status', 'success');
            $json->has('message');
        });

        $this->assertDatabaseHas((new CustomerNote())->getTable(), [
            'customer_id' => $data['customer_id'],
            'note' => $data['note']
        ]);
    }


    /**
     * Test delete a customer note
     *
     * @return void
     */
    public function test_delete_customer_note(): void
    {
        $this->authenticateAsOwner();

        $customer = Customer::factory()->create();
        $customerNote = CustomerNote::factory()->for($customer)->create();

        $response = $this->deleteJson(self::MODULE_BASE_URL . '/delete', [
            'id' => $customerNote->id,
        ]);

        $response->assertOk();
        $this->assertResponseStatusSuccess($response);

        $this->assertSoftDeleted((new CustomerNote())->getTable(), [
            'id' => $customerNote->id
        ]);
    }

    /**
     * Test force delete a customer note
     *
     * @return void
     */
    public function test_force_delete_customer_note(): void
    {
        $this->authenticateAsOwner();

        $customer = Customer::factory()->create();
        $customerNote = CustomerNote::factory()->for($customer)->create();

        $response = $this->deleteJson(self::MODULE_BASE_URL . '/delete', [
            'id' => $customerNote->id,
            'force' => true
        ]);

        $response->assertOk();
        $this->assertResponseStatusSuccess($response);

        $this->assertDatabaseMissing((new CustomerNote())->getTable(), [
            'id' => $customerNote->id
        ]);
    }

    /**
     * Test restore a customer note
     *
     * @return void
     */
    public function test_restore_customer_note(): void
    {
        $this->authenticateAsOwner();
        $customer = Customer::factory()->create();
        $customerNote = CustomerNote::factory()->for($customer)->softDeleted()->create();
        $response = $this->patchJson(self::MODULE_BASE_URL . '/restore', [
            'id' => $customerNote->id,
        ]);
        $response->assertOk();
        $this->assertInstanceReturnedInResponse($response, 'customer_note', CustomerNoteResource::class);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('customer_note');
            $json->has('customer_note.id');

            // status meta
            $json->where('status', 'success');
            $json->has('message');
        });

        $this->assertNotSoftDeleted((new CustomerNote())->getTable(), [
            'id' => $customerNote->id,
        ]);
    }
}
