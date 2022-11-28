<?php

namespace Database\Factories;

use App\Models\Company\Company;
use App\Models\Setting\WorkContractSetting;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;

class WorkContractSettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WorkContractSetting::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        return $this->afterMaking(function (WorkContractSetting $workContractSetting) {
            if (!$workContractSetting->company_id) {
                $company = Company::factory()->create();
                $workContractSetting->company()->associate($company);
            }
            $workContractSetting->clearMediaCollection();
            $workContractSetting->addMedia(UploadedFile::fake()->image('logo.png'))->toMediaCollection('signature');
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'company_id' => $this->faker->word(),
            'footer' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
