<?php

namespace Database\Factories;

use App\Enums\PaymentTerm\PaymentTermStatus as Status;
use App\Models\{Company\Company, Invoice\Invoice, PaymentPickup\PaymentTerm};
use App\Traits\FactoryDeletedState;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentTermFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaymentTerm::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (PaymentTerm $term) {
            if (!$term->company_id) {
                $company = Company::factory()->create();
                $term->company()->associate($company);
            }
            if (!$term->invoice_id) {
                $company = Company::findOrFail($term->company_id);
                $invoice = Invoice::factory()
                    ->for($company)
                    ->create();
                $term->invoice()->associate($invoice);
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
            'term_name' => $this->faker->word,
            'status' => Status::Unpaid,
            'invoice_portion' => rand(1, 100),
            'amount' => rand(10, 100),
            'due_date' => $this->faker->date('Y-m-d H:i:s'),
        ];
    }

    /**
     * Indicate that the work created will have status of Unpaid
     *
     * @return Factory
     */
    public function unpaid()
    {
        return $this->state(function (array $attributes) {
            return ['status' => Status::Unpaid];
        });
    }

    /**
     * Indicate that the work created will have status of Paid
     *
     * @return Factory
     */
    public function paid()
    {
        return $this->state(function (array $attributes) {
            return ['status' => Status::Paid];
        });
    }

    /**
     * Indicate that the work created will have status of Forwarded
     * to Debt collector
     *
     * @return Factory
     */
    public function forwardedToDebtCollector()
    {
        return $this->state(function (array $attributes) {
            return ['status' => Status::ForwardedToDebtCollector];
        });
    }
}
