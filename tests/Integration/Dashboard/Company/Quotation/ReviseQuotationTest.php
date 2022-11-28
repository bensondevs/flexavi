<?php

namespace Tests\Integration\Dashboard\Company\Quotation;

use App\Enums\Quotation\QuotationStatus;
use App\Jobs\SendMail;
use App\Mail\Quotation\QuotationMail;
use App\Models\Customer\Customer;
use App\Models\Quotation\Quotation;
use App\Models\User\User;
use App\Models\WorkService\WorkService;
use Illuminate\Foundation\Testing\{WithFaker};
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ReviseQuotationTest extends TestCase
{
    use WithFaker;

    /**
     * Testing send quotation to a customer after the customer request a quotation
     *
     * @return void
     */
    public function test_send_it_directly_after_quotation_created(): void
    {
        \Queue::fake();
        \Mail::fake();
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;
        $customer = Customer::factory()->for($company)->create();
        $quotation = Quotation::factory()->for($company)->for($customer)->drafted()->createQuietly();
        $response = $this->patchJson('/api/dashboard/companies/quotations/send', [
            'quotation_id' => $quotation->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
        });

        $this->assertDatabaseHas((new QUotation())->getTable(), [
            'id' => $quotation->id,
            'status' => QuotationStatus::Sent,
        ]);

        \Queue::assertPushed(SendMail::class, function ($job) use ($customer) {
            return $job->mailable instanceof QuotationMail && $job->destination === $customer->email;
        });
    }


    /**
     * Test update quotation potential amount
     *
     * @return void
     */
    public function test_update_potential_fixed_price_on_quotation(): void
    {
        Queue::fake();
        Mail::fake();

        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );
        $company = $user->owner->company;
        $customer = Customer::factory()->for($company)->create();

        $quotation = Quotation::factory()
            ->for($customer)
            ->for($company)
            ->draft()
            ->createQuietly();

        $workServices = WorkService::factory()->for($company)->count(rand(1, 5))->create();

        $input = [
            'quotation_id' => $quotation->id,
            'customer_id' => $customer->id,
            'number' => random_string(10),
            'date' => carbon()->now()->toDateString(),
            'expiry_date' => carbon()->now()->addDays(rand(1, 3))->toDateString(),
            'customer_address' => $this->faker->address(),
            'note' => $this->faker->randomElement([
                null, $this->faker->words(3, true)
            ]),
            'vat_percentage' => 20,
            'discount_amount' => 100,
            'potential_amount' => 500,
            'signature' => UploadedFile::fake()->image('signature.png'),
            'status' => QuotationStatus::Sent,
            'work_services' => $workServices->map(function ($workService) use ($workServices) {
                return [
                    'work_service_id' => $workService->id,
                    'amount' => $workServices->count(),
                ];
            })->toArray(),
        ];

        $response = $this->putJson('/api/dashboard/companies/quotations/update', $input);

        $response->assertStatus(200);

        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
        });

        $this->assertDatabaseHas((new Quotation())->getTable(), [
            'number' => $input['number'],
            'customer_id' => $input['customer_id'],
            'status' => $input['status'],
        ]);

        \Queue::assertPushed(SendMail::class, function ($job) use ($customer) {
            return $job->mailable instanceof QuotationMail && $job->destination === $customer->email;
        });

    }
}
