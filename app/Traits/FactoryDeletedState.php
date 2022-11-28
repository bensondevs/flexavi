<?php

namespace App\Traits;

trait FactoryDeletedState
{
    /**
     * Indicate that the current state is soft deleted
     *
     * @return $this
     */
    public function softDeleted(): static
    {
        $faker = $this->faker;

        return $this->state(function (array $attributes) use ($faker) {
            return ['deleted_at' => $faker->datetime()];
        });
    }
}
