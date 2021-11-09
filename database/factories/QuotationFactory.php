<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Traits\FactoryDeletedState;

use App\Models\{ Quotation, Appointment, Customer, Company };

use App\Enums\Quotation\{
    QuotationType as Type,
    QuotationStatus as Status,
    QuotationCanceller as Canceller,
    QuotationDamageCause as DamageCause,
    QuotationPaymentMethod as PaymentMethod
};

class QuotationFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Quotation::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Quotation $quotation) {
            if (! $quotation->company_id) {
                $company = Company::factory()->create();
                $quotation->company()->associate($company);
            }

            if (! $quotation->customer_id) {
                $customer = Customer::factory()->for($quotation->company)->create();
                $quotation->customer()->associate($customer);
            }

            if (! $quotation->appointment_id) {
                $appointment = Appointment::factory()->for($quotation->company)->create();
                $quotation->appointment()->associate($appointment);
            }
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = $this->faker;

        return [
            'type' => rand(Type::Leakage, Type::Renewal),

            'damage_causes' => [rand(DamageCause::Leak, DamageCause::OverdueMaintenance)],

            'quotation_date' => $faker->date(),
            'quotation_number' => $faker->randomNumber(5, true),
            
            'contact_person' => $faker->name(),

            'address' => $faker->address(),
            'zip_code' => $faker->postcode(),
            'phone_number' => $faker->phoneNumber(),
            
            'quotation_description' => $faker->word(),

            'vat_percentage' => $faker->randomNumber(2, false),
            'discount_amount' => $faker->randomNumber(3, false),
            'amount' => $faker->randomNumber(4, false),

            'expiry_date' => $faker->datetime(),
            'status' => rand(Status::Draft, Status::Cancelled),

            'payment_method' => rand(PaymentMethod::Cash, PaymentMethod::BankTransfer),
        ];
    }

    /**
     * Indicate that the model's that has type of leakage.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function leakage()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => Type::Leakage,
            ];
        });
    }

    /**
     * Indicate that the model's that has type of renovation.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function renovation()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => Type::Renovation,
            ];
        });
    }

    /**
     * Indicate that the model's that has type of reparation.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function reparation()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => Type::Reparation,
            ];
        });
    }

    /**
     * Indicate that the model's that has type renewal.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function renewal()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => Type::Renewal,
            ];
        });
    }

    /**
     * Indicate that the model's that has damage cause of leak.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function leak()
    {
        return $this->state(function (array $attributes) {
            $damageCauses = $attributes['damage_causes'];
            array_push($damageCauses, DamageCause::Leak);
            return [
                'damage_causes' => $damageCauses,
            ];
        });
    }

    /**
     * Indicate that the model's that has status of draft.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function draft()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::Draft,
            ];
        });
    }

    /**
     * Indicate that the model's that has status of sent.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function sent()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::Sent,
            ];
        });
    }

    /**
     * Indicate that the model's that has status of revised.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function revised()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::Revised,
            ];
        });
    }

    /**
     * Indicate that the model's that has status of honored.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function honored()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::Honored,
            ];
        });
    }

    /**
     * Indicate that the model's that has status of cancelled by customer.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function cancelledByCustomer()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::Cancelled,
                'canceller' => Canceller::Customer
            ];
        });
    }

    /**
     * Indicate that the model's that has status of cancelled by customer.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function cancelledByCompany()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::Cancelled,
                'canceller' => Canceller::Company
            ];
        });
    }
}
