<?php

namespace App\Traits;

trait FactoryDeletedState 
{
    public function softDeleted()
    {
        $faker = $this->faker;
        return $this->state(function (array $attributes) use ($faker) {
            return ['deleted_at' => $faker->datetime()];
        });
    }
}