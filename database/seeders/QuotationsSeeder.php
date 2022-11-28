<?php

namespace Database\Seeders;

use App\Enums\Quotation\QuotationStatus;
use App\Models\Customer\Customer;
use App\Models\Quotation\Quotation;
use App\Models\Quotation\QuotationItem;
use App\Models\WorkService\WorkService;
use Faker\Factory;
use Illuminate\Database\Seeder;

class QuotationsSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $faker = Factory::create();
        Quotation::whereNotNull('id')->forceDelete();
        $faker = Factory::create();
        $rawQuotations = [];
        $rawQuotationItems = [];
        foreach (Customer::get() as $customer) {
            for ($i = 0; $i < 5; $i++) {
                $quotationId = generateUuid();
                $createdAt = now()->copy()->subDays(rand(5, 20));

                $rawQuotations[] = [
                    'id' => $quotationId,
                    'customer_id' => $customer->id,
                    'company_id' => $customer->company_id,
                    'number' => $number = randomString(10),
                    'date' => now(),
                    'expiry_date' => now()->addDays(30),
                    'status' => QuotationStatus::getRandomValue(),
                    'amount' => rand(100, 300),
                    'discount_amount' => rand(10, 30),
                    'potential_amount' => $faker->randomElement([0, rand(100, 300)]),
                    'total_amount' => rand(100, 300),
                    'customer_address' => $faker->address,
                    'note' => $faker->text,
                    'sent_at' => rand(0, 1) ? $createdAt->copy()->addDays(rand(6, 10)) : null,
                    'nullified_at' => rand(0, 1) ? $createdAt->copy()->addDays(rand(6, 10)) : null,
                    'signed_at' => rand(0, 1) ? $createdAt->copy()->addDays(rand(6, 10)) : null,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt->copy()->addDays(rand(1, 5)),
                    'deleted_at' => $i % 2 == 0 ?
                        $createdAt->copy()->addDays(rand(6, 10))
                        : null,
                ];

                for ($j = 0; $j < rand(2, 3); $j++) {
                    $rawQuotationItems[] = [
                        'id' => generateUuid(),
                        'quotation_id' => $quotationId,
                        'tax_percentage' => rand(0, 10),
                        'work_service_id' => WorkService::where('company_id', $customer->company_id)->inRandomOrder()->first()->id,
                        'unit_price' => rand(10, 100),
                        'amount' => rand(10, 100),
                        'total' => rand(100, 300),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        foreach (array_chunk($rawQuotations, 1000) as $rawQuotationsChunk) {
            Quotation::insert($rawQuotationsChunk);
        }

        foreach (array_chunk($rawQuotationItems, 1000) as $rawQuotationItemsChunk) {
            QuotationItem::insert($rawQuotationItemsChunk);
        }

        foreach (Quotation::withTrashed()->get() as $quotation) {
            $quotation->countWorksAmount();
        }

    }
}
