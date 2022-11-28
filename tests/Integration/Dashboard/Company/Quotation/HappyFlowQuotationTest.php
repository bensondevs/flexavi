<?php

namespace Tests\Integration\Dashboard\Company\Quotation;

use App\Enums\Quotation\QuotationStatus;
use App\Jobs\SendMail;
use App\Mail\Quotation\QuotationMail;
use App\Models\Customer\Customer;
use App\Models\Quotation\Quotation;
use App\Models\User\User;
use App\Models\WorkService\WorkService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\{WithFaker};
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class HappyFlowQuotationTest extends TestCase
{
    use WithFaker;

    /**
     * Testing send quotation to a customer after the customer request a quotation
     *
     * @return void
     */
    public function test_send_it_directly_after_quotation_created(): void
    {
        Queue::fake();
        Mail::fake();
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;
        $customer = Customer::factory()->for($company)->create([
            'email' => 'customer' . random_string(5) . '@flexavi.nl',
        ])->refresh();
        $quotation = Quotation::factory()
            ->for($company)
            ->for($customer)
            ->draft()
            ->createQuietly(['customer_id' => $customer->id]);
        $quotation->refresh();

        $inputData = ['quotation_id' => $quotation->id];
        foreach (WorkService::factory(3)->for($company)->create() as $workService) {
            $inputData['work_services'][] = [
                'work_service_id' => $workService->id,
                'amount' => rand(1, 5),
            ];
        }
        $response = $this->postJson(
            '/api/dashboard/companies/quotations/send',
            $inputData,
        );

        $response->assertSuccessful();
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
        });

        $this->assertDatabaseHas((new QUotation())->getTable(), [
            'id' => $quotation->id,
            'status' => QuotationStatus::Sent,
        ]);

        Queue::assertPushed(SendMail::class, function ($job) use ($customer) {
            return $job->mailable instanceof QuotationMail && $job->destination === $customer->email;
        });
    }

    /**
     * Testing print
     *
     * @return void
     */
    public function test_print_or_sent_after_quotation_created(): void
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
            ->draft()
            ->for($company)
            ->for($customer)
            ->createQuietly();
        $quotation->refresh();

        $inputData = ['quotation_id' => $quotation->id];
        foreach (WorkService::factory(3)->for($company)->create() as $workService) {
            $inputData['work_services'][] = [
                'work_service_id' => $workService->id,
                'amount' => rand(1, 5),
            ];
        }
        $response = $this->postJson(
            '/api/dashboard/companies/quotations/send',
            $inputData,
        );

        $response->assertSuccessful();
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
        });

        $this->assertDatabaseHas((new Quotation())->getTable(), [
            'id' => $quotation->id,
            'status' => QuotationStatus::Sent,
        ]);

        Queue::assertPushed(SendMail::class, function ($job) use ($customer) {
            return $job->mailable instanceof QuotationMail && $job->destination === $customer->email;
        });
    }

    /**
     * Testing upload signed document
     *
     * @return void
     */
    public function test_upload_signed_doc_on_quotation_sent_status(): void
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
        $quotation = Quotation::factory()->for($company)->for($customer)->sent()->createQuietly();
        $response = $this->postJson('/api/dashboard/companies/quotations/signed_document/upload', [
            'quotation_id' => $quotation->id,
            'signed_document' => UploadedFile::fake()->create('signed_doc.pdf', 1000),
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
        });

        $this->assertDatabaseHas((new QUotation())->getTable(), [
            'id' => $quotation->id,
            'status' => QuotationStatus::Signed,
        ]);
    }
}
