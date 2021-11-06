<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Traits\FactoryDeletedState;

use App\Models\{ Company, Customer, Owner, Employee, Address };

use App\Enums\Address\AddressType;

class AddressFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Address::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = $this->faker;

        // Addressable
        $addressableTypes = [
            Company::class,
            Customer::class,
            Owner::class,
            Employee::class,
        ];
        $addressableType = $faker->randomElement($addressableTypes);
        $addressable  = (new $addressableType)->factory()->create();

        // Address Type
        $type = rand(AddressType::VisitingAddress, AddressType::Other);
        $otherAddressTypeDescription = ($type == AddressType::Other) ?
            $faker->name : '';

        return [
            'addressable_type' => $addressableType,
            'addressable_id' => $addressable->id,

            'address_type' => $type,
            'other_address_type_description' => $otherAddressTypeDescription,

            'address' => $faker->streetAddress,
            'house_number' => $faker->buildingNumber,
            'house_number_suffix' => rand(0, 1) ?
                strtoupper($faker->lexify('?')) : null,
            'zipcode' => $faker->postcode,
            'city' => $faker->city,
            'province' => $faker->state,
        ];
    }

    /**
     * Indicate that the model's belongs to Company.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function company()
    {
        return $this->state(function (array $attributes) {
            $company = Company::inRandomOrder()->first() ?: 
                Company::factory()->create();
            return [
                'addressable_type' => Company::class,
                'addressable_id' => $company->id,
            ];
        });
    }

    /**
     * Indicate that the model's belongs to Customer.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function customer()
    {
        return $this->state(function (array $attributes) {
            $customer = Customer::inRandomOrder()->first() ?: 
                Customer::factory()->create();
            return [
                'addressable_type' => Customer::class,
                'addressable_id' => $customer->id,
            ];
        });
    }

    /**
     * Indicate that the model's belongs to Owner.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function owner()
    {
        return $this->state(function (array $attributes) {
            $owner = Ownere::inRandomOrder()->first() ?: 
                Owner::factory()->create();
            return [
                'addressable_type' => Owner::class,
                'addressable_id' => Owner::factory()->create()->id,
            ];
        });
    }

    /**
     * Indicate that the model's belongs to Employee.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function employee()
    {
        return $this->state(function (array $attributes) {
            $employee = Employee::inRandomOrder()->first() ?:
                Employee::factory()->create();
            return [
                'addressable_type' => Employee::class,
                'addressable_id' => Employee::factory()->create()->id,
            ];
        });
    }

    /**
     * Indicate that the model's that has type of Visiting Address.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function visitingAddress()
    {
        return $this->state(function (array $attributes) {
            return [
                'address_type' => AddressType::VisitingAddress,
            ];
        });
    }

    /**
     * Indicate that the model's that has type of Invoicing Address.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function invoicingAddress()
    {
        return $this->state(function (array $attributes) {
            return [
                'address_type' => AddressType::InvoicingAddress,
            ];
        });
    }

    /**
     * Indicate that the model's that assigned to worklist.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function otherAddress()
    {
        return $this->state(function (array $attributes) {
            return [
                'address_type' => AddressType::Other,
                'other_address_type_description' => $this->faker->words(2, true),
            ];
        });
    }
}
