<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Traits\FactoryDeletedState;

use App\Models\{ 
    PaymentPickup, 
    PaymentPickupable, 
    Invoice, 
    Revenue, 
    PaymentTerm 
};

class PaymentPickupableFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaymentPickupable::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (PaymentPickupable $pickupable) {
            if (! $pickupable->payment_pickup_id) {
                $pickup = PaymentPickup::factory()->create();
                $pickupable->payment_pickup_id = $pickup->id;
            }

            if (! $pickupable->payment_pickupable_id) {
                $paymentPickup = PaymentPickup::findOrFail($pickupable->payment_pickup_id);
                $company = $paymentPickup->company;
                $invoice = Invoice::factory()->for($company)->create();

                $paymentPickup->payment_pickupable_type = Invoice::class;
                $paymentPickup->payment_pickupable_id = $invoice->id;
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
        return [
            //
        ];
    }

    /**
     * Indicate that the payment pickupable created will have pickupable type
     * of \App\Models\Invoice
     * 
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function invoice()
    {
        return $this->state(function (array $attributes) {
            if ($pickupId = $this->states['payment_pickup_id']) {
                $pickup = PaymentPickup::with('company')->findOrFail($pickupId);
                $company = $pickup->company;
            } else {
                $company = Company::inRandomOrder()->first();
                $pickupId = PaymentPickup::factory()
                    ->for($company)
                    ->create()
                    ->id;
            }
            $invoice = Invoice::factory()->for($company)->create();

            return [
                'payment_pickup_id' => $pickupId,
                'payment_pickupable_id' => $invoice->id,
                'payment_pickupable_type' => Invoice::class,
            ];
        });
    }

    /**
     * Indicate that the payment pickupable created will have pickupable type
     * of \App\Models\PaymentTerm
     * 
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function paymentTerm()
    {
        return $this->state(function (array $attributes) {
            if ($pickupId = $this->states['payment_pickup_id']) {
                $pickup = PaymentPickup::with('company')->findOrFail($pickupId);
                $company = $pickup->company;
            } else {
                $company = Company::inRandomOrder()->first();
                $pickupId = PaymentPickup::factory()
                    ->for($company)
                    ->create()
                    ->id;
            }
            $paymentTerm = PaymentTerm::factory()
                ->for($company)
                ->create();

            return [
                'payment_pickup_id' => $pickupId,
                'payment_pickupable_id' => $paymentTerm->id,
                'payment_pickupable_type' => PaymentTerm::class,
            ];
        });
    }

    /**
     * Indicate that the payment pickupable created will have pickupable type
     * of \App\Models\Revenue
     * 
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function revenue()
    {
        return $this->state(function (array $attributes) {
            if ($pickupId = $this->states['payment_pickup_id']) {
                $pickup = PaymentPickup::with('company')->findOrFail($pickupId);
                $company = $pickup->company;
            } else {
                $company = Company::inRandomOrder()->first();
                $pickupId = PaymentPickup::factory()
                    ->for($company)
                    ->create()
                    ->id;
            }
            $revenue = Revenue::factory()
                ->for($company)
                ->create();

            return [
                'payment_pickup_id' => $pickupId,
                'payment_pickupable_id' => $revenue->id,
                'payment_pickupable_type' => Revenue::class,
            ];
        });
    }
}
