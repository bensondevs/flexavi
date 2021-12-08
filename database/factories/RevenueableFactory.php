<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Traits\FactoryDeletedState;

use App\Models\{ 
    Company, 
    Revenue, 
    Revenueable, 
    Work, 
    InvoiceItem 
};

class RevenueableFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Revenueable::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Revenueable $revenueable) {
            if (! $revenueable->revenue_id) {
                $revenue = Revenue::factory()->create();
                $revenueable->revenue()->associate($revenue);
            }

            /*if (! $revenueable->revenueable_id) {
                $types = [new Work, new InvoiceItem];
                $selectedType = $this->faker->randomElement($types);
                $company = Company::findOrFail($revenueable->revenue->company_id);
                $attachable = $selectedType::factory()->for($company)->created();

                $revenueable->revenueable_id = $attachable->id;
                $revenueable->revenueable_type = $selectedType;
            }*/
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
     * Indicate model appointment has relationship with work.
     * 
     * @param \App\Models\Work|null
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function work($work = null)
    {
        return $this->state(function (array $attributes) use ($work) {
            if (! $work) $work = Work::factory()->create();

            return [
                'revenueable_id' => $work->id,
                'revenueable_type' => $work->type,
            ];
        });
    }
}
