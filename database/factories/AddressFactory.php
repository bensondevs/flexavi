<?php

namespace Database\Factories;

use App\Enums\Address\AddressType;
use App\Models\{Address\Address, Company\Company, Customer\Customer, Employee\Employee, Owner\Owner};
use App\Traits\FactoryDeletedState;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        $faker = $this->faker;
        return $this->afterMaking(function (Address $address) use ($faker) {
            if (!$address->addressable_id) {
                $addressableTypes = [
                    Company::class,
                    Customer::class,
                    Owner::class,
                    Employee::class,
                ];
                $addressableType = $this->faker->randomElement(
                    $addressableTypes
                );
                $addressable = (new $addressableType())->factory()->create();
                $address->addressable()->attach($addressable);
            }
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $type = rand(AddressType::VisitingAddress, AddressType::Other);
        $otherAddressTypeDescription =
            $type == AddressType::Other
                ? 'Address ' . $this->faker->name
                : null;

        return [
            'address_type' => $type,
            'other_address_type_description' => $otherAddressTypeDescription,
            'address' => $this->faker->address,
            'house_number' => $this->faker->randomNumber(5, true),
            'house_number_suffix' => Str::title($this->faker->randomLetter()),
            'zipcode' => $this->faker->randomNumber(5, true),
            'city' => $this->faker->city,
            'province' => $this->faker->state,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->latitude,
        ];
    }

    /**
     * Indicate that the model's belongs to Company.
     *
     * @param Company $company
     * @return Factory
     */
    public function company(Company $company): Factory
    {
        return $this->state(function (array $attributes) use ($company) {
            return [
                'addressable_type' => Company::class,
                'addressable_id' => $company->id,
            ];
        });
    }

    /**
     * Indicate that the model's belongs to Customer.
     *
     * @param Customer $customer
     * @return Factory
     */
    public function customer(Customer $customer): Factory
    {
        return $this->state(function (array $attributes) use ($customer) {
            return [
                'addressable_type' => Customer::class,
                'addressable_id' => $customer->id,
            ];
        });
    }

    /**
     * Indicate that the model's belongs to Owner.
     *
     * @param Owner $owner
     * @return Factory
     */
    public function owner(Owner $owner): Factory
    {
        return $this->state(function (array $attributes) use ($owner) {
            return [
                'addressable_type' => Owner::class,
                'addressable_id' => $owner->id,
            ];
        });
    }

    /**
     * Indicate that the model's belongs to Employee.
     * @param Employee $employee
     * @return Factory
     */
    public function employee(Employee $employee): Factory
    {
        return $this->state(function (array $attributes) use ($employee) {
            return [
                'addressable_type' => Employee::class,
                'addressable_id' => $employee->id,
            ];
        });
    }

    /**
     * Indicate that the model's that has type of Visiting Address.
     *
     * @return Factory
     */
    public function visitingAddress(): Factory
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
     * @return Factory
     */
    public function invoicingAddress(): Factory
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
     * @return Factory
     */
    public function otherAddress(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'address_type' => AddressType::Other,
                'other_address_type_description' => $this->faker->words(
                    2,
                    true
                ),
            ];
        });
    }
}
