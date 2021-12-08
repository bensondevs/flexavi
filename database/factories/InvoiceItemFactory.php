<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Traits\FactoryDeletedState;

use App\Models\{ Owner, User, Company, InvoiceItem };

class InvoiceItemFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InvoiceItem::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (InvoiceItem $item) {
            if (! $item->company_id) {
                $company = Company::inRandomOrder()->first();
                $item->company()->associate($company);
            }

            if (! $item->invoice_id) {
                $invoice = Invoice::factory()->create([
                    'company_id' => $item->company_id
                ]);
                $item->invoice()->associate($invoice);
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
            'item_name' => $faker->word(),
            'description' => $faker->word(),
            'quantity' => rand(1, 100),
            'quantity_unit' => $faker->randomElement(['m2', 'cm', 'pcs']),
            'amount' => rand(10, 1000),
        ];
    }
}
