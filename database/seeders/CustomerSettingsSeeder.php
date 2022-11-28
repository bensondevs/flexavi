<?php

namespace Database\Seeders;

use App\Models\Company\Company;
use App\Models\Setting\CustomerSetting;
use Faker\Generator;
use Illuminate\Database\Seeder;

class CustomerSettingsSeeder extends Seeder
{
    /**
     * The current Faker instance.
     *
     * @var Generator
     */
    protected mixed $faker;

    /**
     * Create a new seeder instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->faker = app(Generator::class);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        CustomerSetting::query()->delete();

        $raws = [] ;

        array_push($raws, $this->generateDefaultCustomerSetting());

        foreach (Company::withTrashed()->get() as $company) {
            array_push($raws, $this->generateCustomerSetting($company));
        }

        foreach (array_chunk($raws, 50) as $chunk) {
            CustomerSetting::insert($chunk);
        }
    }

    /**
     * Generate Employee settings.
     *
     * @param Company $company
     * @return array
     */
    private function generateCustomerSetting(Company $company): array
    {
        return [
            'id' => generateUuid(),
            'company_id' => $company->id,
            'pagination' => $this->faker->randomElement([10,20,50,100]),
        ];
    }

    /**
     * Generate default Employee settings.
     *
     * @return array
     */
    private function generateDefaultCustomerSetting(): array
    {
        return [
            'id' => generateUuid(),
            'company_id' => null,
            'pagination' => 10,
        ];
    }
}
