<?php

namespace Database\Seeders;

use App\Models\Company\Company;
use App\Models\Setting\CompanySetting;
use Faker\Generator;
use Illuminate\Database\Seeder;

class CompanySettingsSeeder extends Seeder
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
        CompanySetting::query()->delete();

        $raws = [] ;

        array_push($raws, $this->generateDefaultCompanySetting());

        foreach (Company::withTrashed()->get() as $company) {
            array_push($raws, $this->generateCompanySetting($company));
        }

        foreach (array_chunk($raws, 50) as $chunk) {
            CompanySetting::insert($chunk);
        }
    }

    /**
     * Generate company settings.
     *
     * @param Company $company
     * @return array
     */
    private function generateCompanySetting(Company $company): array
    {
        return [
            'id' => generateUuid(),
            'company_id' => $company->id,
            'auto_subs_same_plan_while_ends' => $this->faker->randomElement([true , false]),
            'invoicing_address_same_as_visiting_address' => $this->faker->randomElement([true , false])
        ];
    }

    /**
     * Generate default company settings.
     *
     * @return array
     */
    private function generateDefaultCompanySetting(): array
    {
        return [
            'id' => generateUuid(),
            'company_id' => null,
            'auto_subs_same_plan_while_ends' => false,
            'invoicing_address_same_as_visiting_address' => true
        ];
    }
}
