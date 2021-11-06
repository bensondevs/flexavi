<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Traits\FactoryDeletedState;

use App\Models\Company;

class CompanyFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = $this->faker;
        return [
            'company_name' => $faker->company,
            'email' => $faker->safeEmail,
            'phone_number' => $faker->phoneNumber,
            'vat_number' => $faker->randomNumber(5, true),
            'commerce_chamber_number' => rand(1, 100),
            'company_logo_path' => $faker->image(
                storage_path('app/public/uploads/companies/logos'), 
                400, 
                300, 
                null, 
                false
            ),
            'company_website_url' => $faker->url,
        ];
    }
}
