<?php

namespace Database\Factories;

use App\Models\{Revenue\Revenue, Revenue\Revenueable, Work\Work};
use App\Traits\FactoryDeletedState;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            if (!$revenueable->revenue_id) {
                $revenue = Revenue::factory()->create();
                $revenueable->revenue()->associate($revenue);
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
        return [];
    }

    /**
     * Indicate model appointment has relationship with work.
     *
     * @param Work|null
     * @return Factory
     */
    public function work($work = null)
    {
        return $this->state(function (array $attributes) use ($work) {
            if (!$work) {
                $work = Work::factory()->create();
            }

            return [
                'revenueable_id' => $work->id,
                'revenueable_type' => $work->type,
            ];
        });
    }
}
