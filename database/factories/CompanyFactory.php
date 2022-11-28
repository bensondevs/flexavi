<?php

namespace Database\Factories;

use App\Models\{Address\Address, Company\Company, Subscription\Subscription};
use App\Traits\FactoryDeletedState;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\{Storage};

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
     * Indicate that created company will be generated with
     * address.
     *
     * @var bool
     */
    private bool $withAddresses = true;

    /**
     * Indicate that created company will be generated with
     * subscription.
     *
     * @var bool
     */
    private bool $withSubscription = true;

    /**
     * Subscription instance container property.
     *
     * @var Subscription|null
     */
    private ?Subscription $subscription = null;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        // create upload directory with the right permission
        $path = Storage::path('companies');
        if (!Storage::exists($path)) {
            Storage::makeDirectory($path);
        }

        return $this->afterCreating(function (Company $company) {
            /**
             * Generate addresses for the company.
             */
            if ($this->withAddresses) {
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
            'company_name' => $this->faker->unique()->company,
            'email' => $this->faker->unique()->safeEmail,
            'phone_number' => $this->faker->unique()->phoneNumber,
            'vat_number' => $this->faker->randomNumber(5, true),
            'commerce_chamber_number' => $this->faker->numberBetween(1, 100),
            'company_website_url' => $this->faker->url,
        ];
    }

    /**
     * Indicate that the created company will be generated with addresses.
     *
     * @param bool $withAddresses
     * @return Factory
     */
    public function withAddresses(bool $withAddresses = true): Factory
    {
        $this->withAddresses = $withAddresses;

        return $this;
    }

    /**
     * Indicate that the created company will be generated with subscription.
     *
     * @param bool $withSubscription
     * @return Factory
     */
    public function withSubscription(bool $withSubscription = true): Factory
    {
        $this->withSubscription = $withSubscription;

        return $this;
    }

    /**
     * Set the subscription instance that will be attached to the company.
     *
     * @param Subscription $subscription
     * @return Factory
     */
    public function subscription(Subscription $subscription): Factory
    {
        $this->subscription = $subscription;

        return $this;
    }

    /**
     * Indicate that generated company will be deleted in given days.
     *
     * @param int $days
     * @return Factory
     */
    public function willBeDeleted(int $days): Factory
    {
        return $this->state(function () use ($days) {
            return [
                'deleted_at' => now()->toDateTimeString(),
                'will_be_permanently_deleted_at' => now()
                    ->addDays($days)
                    ->toDateTimeString(),
            ];
        });
    }
}
