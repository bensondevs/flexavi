<?php

namespace Tests\Unit\Factories\Customer;

use App\Enums\Customer\{CustomerAcquisition, CustomerSalutation};
use App\Models\{Address\Address, Customer\Customer};
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use WithFaker;

    /**
     * Test create a company customer instance
     *
     * @return void
     */
    public function test_create_company_customer_instance()
    {
        // make an instance
        $customer = Customer::factory()->create();

        // assert the instance
        $this->assertNotNull($customer);
        $this->assertModelExists($customer);
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'company_id' => $customer->company_id,
            'salutation' => $customer->salutation,
            'fullname' => $customer->fullname,
            'email' => $customer->email,
            'phone' => $customer->phone,
            'second_phone' => $customer->second_phone,
            'acquired_through' => $customer->acquired_through,
        ]);

        // setup model relations
        $address = Address::factory()
            ->customer($customer)
            ->create();

        // assert the model relations
        $this->assertNotNull($customer->company);
        $this->assertModelExists($customer->company);
        $this->assertNotNull($customer->address);
        $this->assertModelExists($customer->address);
        $this->assertNotEmpty($customer->addresses);
    }

    /**
     * Test create multiple company customer instances
     *
     * @return void
     */
    public function test_create_multiple_company_customer_instances()
    {
        // make the instances
        $count = 10;
        $customers = Customer::factory($count)->create();

        // assert the instances
        $this->assertTrue(count($customers) === $count);
    }

    /**
     * Test update a company customer instance
     *
     * @return void
     */
    public function test_update_company_customer_instance()
    {
        // make an instance
        $customer = Customer::factory()->create();

        // assert the instance
        $this->assertNotNull($customer);
        $this->assertModelExists($customer);
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'company_id' => $customer->company_id,
            'salutation' => $customer->salutation,
            'fullname' => $customer->fullname,
            'email' => $customer->email,
            'phone' => $customer->phone,
            'second_phone' => $customer->second_phone,
            'acquired_through' => $customer->acquired_through,
        ]);

        // generate dummy data
        $salutation = $this->faker->randomElement([
            CustomerSalutation::Mr,
            CustomerSalutation::Mrs,
        ]);
        $fullname = $this->faker->name;
        $email = $this->faker->safeEmail;
        $phone = $this->faker->phoneNumber;
        $secondPhone = $this->faker->phoneNumber;
        $acquiredThrough = $this->faker->randomElement([
            CustomerAcquisition::Website,
            CustomerAcquisition::Call,
            CustomerAcquisition::Company,
        ]);

        // update instance
        $customer->update([
            'salutation' => $salutation,
            'fullname' => $fullname,
            'email' => $email,
            'phone' => $phone,
            'second_phone' => $secondPhone,
            'acquired_through' => $acquiredThrough,
        ]);

        // assert the updated instance
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'company_id' => $customer->company_id,
            'salutation' => $salutation,
            'fullname' => $fullname,
            'email' => $email,
            'phone' => $phone,
            'second_phone' => $secondPhone,
            'acquired_through' => $acquiredThrough,
        ]);
    }

    /**
     * Test soft delete a company customer instance
     *
     * @return void
     */
    public function test_soft_delete_company_customer_instance()
    {
        // make an instance
        $customer = Customer::factory()->create();

        // assert the instance
        $this->assertNotNull($customer);
        $this->assertModelExists($customer);
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'company_id' => $customer->company_id,
            'salutation' => $customer->salutation,
            'fullname' => $customer->fullname,
            'email' => $customer->email,
            'phone' => $customer->phone,
            'second_phone' => $customer->second_phone,
            'acquired_through' => $customer->acquired_through,
        ]);

        // soft delete the instance
        $customer->delete();

        // assert the soft deleted instance
        $this->assertSoftDeleted($customer);
    }

    /**
     * Test hard delete a company customer instance
     *
     * @return void
     */
    public function test_hard_delete_company_customer_instance()
    {
        // make an instance
        $customer = Customer::factory()->create();

        // assert the instance
        $this->assertNotNull($customer);
        $this->assertModelExists($customer);
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'company_id' => $customer->company_id,
            'salutation' => $customer->salutation,
            'fullname' => $customer->fullname,
            'email' => $customer->email,
            'phone' => $customer->phone,
            'second_phone' => $customer->second_phone,
            'acquired_through' => $customer->acquired_through,
        ]);

        // hard delete the instance
        $customerId = $customer->id;
        $customer->forceDelete();

        // assert the hard deleted instance
        $this->assertModelMissing($customer);
        $this->assertDatabaseMissing('customers', [
            'id' => $customerId,
        ]);
    }

    /**
     * Test restore a trashed company customer instance
     *
     * @return void
     */
    public function test_restore_trashed_company_customer_instance()
    {
        // make an instance
        $customer = Customer::factory()->create();

        // assert the instance
        $this->assertNotNull($customer);
        $this->assertModelExists($customer);
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'company_id' => $customer->company_id,
            'salutation' => $customer->salutation,
            'fullname' => $customer->fullname,
            'email' => $customer->email,
            'phone' => $customer->phone,
            'second_phone' => $customer->second_phone,
            'acquired_through' => $customer->acquired_through,
        ]);

        // soft delete the instance
        $customer->delete();

        // assert the soft deleted instance
        $this->assertSoftDeleted($customer);

        // restore the trashed instance
        $customer->restore();

        // assert the restored instance
        $this->assertNotSoftDeleted($customer);
    }
}
