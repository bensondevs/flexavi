<?php

namespace Database\Seeders;

use App\Enums\Setting\Dashboard\DashboardDefaultResultGraph;
use App\Enums\Setting\DashboardSetting\DashboardResultGraph;
use App\Models\Company\Company;
use App\Models\Setting\DashboardSetting;
use Faker\Generator;
use Illuminate\Database\Seeder;

class DashboardSettingsSeeder extends Seeder
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
        DashboardSetting::query()->delete();

        $raws = [] ;
        // Default Dashboard Settings
        array_push($raws, $this->generateDefaultDashboardSetting());

        foreach (Company::withTrashed()->get() as $company) {
            array_push($raws, $this->generateDashboardSetting($company));
        }

        foreach (array_chunk($raws, 50) as $chunk) {
            DashboardSetting::insert($chunk);
        }
    }

    /**
     * Generate dashboard settings.
     *
     * @param Company $company
     * @return array
     */
    private function generateDashboardSetting(Company $company): array
    {
        return [
            'id' => generateUuid(),
            'company_id' => $company->id,
            'result_graph' => DashboardResultGraph::getRandomValue(),
            'invoice_revenue_date_range' => DashboardDefaultResultGraph::getRandomValue(),
            'best_selling_service_date_range' => rand(1, 10),

        ];
    }

    /**
     * Generate default dashboard settings.
     *
     * @return array
     */
    private function generateDefaultDashboardSetting(): array
    {
        return [
            'id' => generateUuid(),
            'company_id' => null,
            'result_graph' =>  DashboardResultGraph::Weekly,
            'invoice_revenue_date_range' => DashboardDefaultResultGraph::Weekly,
            'best_selling_service_date_range' => 3,

        ];
    }
}
