<?php

namespace Database\Factories;

use App\Enums\Setting\DashboardSetting\DashboardInvoiceRevenueDateRange;
use App\Enums\Setting\DashboardSetting\DashboardResultGraph;
use App\Models\Setting\DashboardSetting;
use App\Traits\FactoryDeletedState;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DashboardSetting>
 */
class DashboardSettingFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DashboardSetting::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        return $this->afterCreating(function (DashboardSetting $dashboardSetting) {
            return $this;
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'result_graph' => DashboardResultGraph::getRandomValue(),
            'invoice_revenue_date_range' => DashboardInvoiceRevenueDateRange::getRandomValue(),
            'best_selling_service_date_range' => rand(1, 10),
        ];
    }
}
