<?php

namespace Database\Seeders;

use App\Enums\SubscriptionPayment\SubscriptionPaymentMethod;
use App\Enums\SubscriptionPayment\SubscriptionPaymentStatus;
use App\Enums\SubscriptionPlanPeriod\DurationType;
use App\Models\{Address\Address,
    Company\Company,
    Subscription\Subscription,
    Subscription\SubscriptionPayment,
    Subscription\SubscriptionPlanPeriod
};
use Illuminate\Database\Seeder;

class CompaniesSeeder extends Seeder
{
    /**
     * Determine the quantity of each company states.
     *
     * @var int
     */
    private int $quantityOfEach = 1;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Seed companies with active subscriptions
        $this->seedActiveCompanies();

        // Seed companies with expired subscriptions
        $this->seedSubscriptionExpiredCompanies();

        // Seed companies that will be deleted soon
        $this->seedWillBeDeletedCompanies();
    }

    /**
     * Seed companies with active subscription that will last for 100 years.
     *
     * @return void
     */
    private function seedActiveCompanies(): void
    {
        $companies = Company::factory($this->quantityOfEach)->create();
        foreach ($companies as $company) {
            // Seed company addresses
            $this->seedCompanyAddresses($company);
        }
    }

    /**
     * Seed company addresses including visiting address and invoicing address.
     *
     * @param Company $company
     * @return void
     */
    private function seedCompanyAddresses(Company $company): void
    {
        // Seed visiting address
        Address::factory()
            ->company($company)
            ->visitingAddress()
            ->create();

        // Seed invoicing address
        Address::factory()
            ->company($company)
            ->invoicingAddress()
            ->create();
    }

    /**
     * Seed companies that the subscription will be expired within several days.
     *
     * @return void
     */
    private function seedSubscriptionExpiredCompanies(): void
    {
        $companies = Company::factory($this->quantityOfEach)->create();
        foreach ($companies as $company) {
            // Seed company addresses
            $this->seedCompanyAddresses($company);
        }
    }

    /**
     * Seed companies that will be permanently deleted.
     *
     * @return void
     */
    private function seedWillBeDeletedCompanies(): void
    {
        $companies = Company::factory($this->quantityOfEach)
            ->willBeDeleted(rand(3, 5))
            ->create();
        foreach ($companies as $company) {
            // Seed company addresses
            $this->seedCompanyAddresses($company);
        }
    }

    /**
     * Seed company subscription records.
     *
     * @param Company $company
     * @param string $state
     * @return void
     */
    private function seedCompanySubscription(
        Company $company,
        string  $state,
    ): void
    {
        // Get subscription plan period
        $planPeriod = SubscriptionPlanPeriod::factory()->create([
            'duration_type' => DurationType::Yearly,
            'duration' => 100,
        ]);

        // Create subscription record for the company
        $subscription = Subscription::factory()->{$state}()->create([
            'company_id' => $company->id,
            'subscription_plan_period_id' => $planPeriod->id,
            'subscription_end' => now()->addYears(100)
        ]);

        // Make payment to the subscription
        SubscriptionPayment::factory()->create([
            'id' => generate_uuid(),
            'subscription_id' => $subscription->id,
            'payment_method' => SubscriptionPaymentMethod::Cash,
            'status' => SubscriptionPaymentStatus::Settled,
        ]);
    }
}
