<?php

namespace Database\Seeders;

use App\Models\Company\Company;
use App\Models\Setting\EmployeeSetting;
use Faker\Generator;
use Illuminate\Database\Seeder;

class EmployeeSettingsSeeder extends Seeder
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
        EmployeeSetting::query()->delete();

        $raws = [] ;

        array_push($raws, $this->generateDefaultEmployeeSetting());

        foreach (Company::withTrashed()->get() as $company) {
            array_push($raws, $this->generateEmployeeSetting($company));
        }

        foreach (array_chunk($raws, 50) as $chunk) {
            EmployeeSetting::insert($chunk);
        }
    }

    /**
     * Generate Employee settings.
     *
     * @param Company $company
     * @return array
     */
    private function generateEmployeeSetting(Company $company): array
    {
        return [
            'id' => generateUuid(),
            'company_id' => $company->id,
            'pagination' => $this->faker->randomElement([10,20,50,100]),
            'use_initials_when_dont_have_avatar' => rand(0, 1),
            'invitation_expiry' => rand(0, 10),
        ];
    }

    /**
     * Generate default Employee settings.
     *
     * @return array
     */
    private function generateDefaultEmployeeSetting(): array
    {
        return [
            'id' => generateUuid(),
            'company_id' => null,
            'pagination' => 10,
            'use_initials_when_dont_have_avatar' => true,
            'invitation_expiry' => 3,
        ];
    }
}
