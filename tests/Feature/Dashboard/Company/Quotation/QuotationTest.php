<?php

namespace Tests\Feature\Dashboard\Company\Quotation;

use App\Enums\Quotation\{QuotationStatus};
use App\Jobs\SendMail;
use App\Mail\Quotation\QuotationMail;
use App\Models\{Customer\Customer,
    Invoice\Invoice,
    Invoice\InvoiceItem,
    Quotation\Quotation,
    Quotation\QuotationItem,
    Quotation\QuotationLog,
    User\User,
    WorkService\WorkService
};
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\Company\Quotation\QuotationController
 *      To see the controller
 */
class QuotationTest extends TestCase
{
    use WithFaker;

    /**
     * Test populate company quotations
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Quotation\QuotationController::companyQuotations()
     *     To see the method
     */
    public function test_populate_company_quotations(): void
    {
        $user = $this->authenticate();

        $company = $user->owner->company;

        for ($i = 1; $i <= 5; $i++) {
            $customer = Customer::factory()->for($company)->create();

            $quotation = Quotation::factory()
                ->for($customer)
                ->for($company)
                ->sent()
                ->createQuietly();

            $workService = WorkService::factory()->for($company)->create();
            QuotationItem::factory()->for($quotation)->for($workService)->count(rand(1, 3))->create();
        }

        $response = $this->getJson(
            urlWithParams('/api/dashboard/companies/quotations', [
                'with_customer' => true
            ])
        );

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('quotations');
            $json->whereType('quotations.data', 'array');

            // pagination meta
            $json->has('quotations.current_page');
            $json->has('quotations.first_page_url');
            $json->has('quotations.from');
            $json->has('quotations.last_page');
            $json->has('quotations.last_page_url');
            $json->has('quotations.links');
            $json->has('quotations.next_page_url');
            $json->has('quotations.path');
            $json->has('quotations.per_page');
            $json->has('quotations.prev_page_url');
            $json->has('quotations.to');
            $json->has('quotations.total');
        });
    }

    /**
     * Authenticate the tester user to access the endpoint.
     *
     * @return User
     */
    private function authenticate(): User
    {
        $user = User::factory()->owner()->create();
        $user->refresh()->load(['owner.company']);

        $this->actingAs($user);

        return $user;
    }

    /**
     * Test populate trashed quotations
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Quotation\QuotationController::trashedQuotations()
     *     To see the method
     */
    public function test_populate_trashed_quotations(): void
    {
        $user = $this->authenticate();

        $company = $user->owner->company;

        for ($i = 1; $i <= 5; $i++) {
            $customer = Customer::factory()->for($company)->create();

            $quotation = Quotation::factory()
                ->for($customer)
                ->for($company)
                ->sent()
                ->softDeleted()
                ->createQuietly();

            $workService = WorkService::factory()->for($company)->create();
            QuotationItem::factory()->for($quotation)->for($workService)->count(rand(1, 3))->create();
        }

        $response = $this->getJson(
            '/api/dashboard/companies/quotations/trasheds'
        );
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('quotations');
            $json->whereType('quotations.data', 'array');

            // pagination meta
            $json->has('quotations.current_page');
            $json->has('quotations.first_page_url');
            $json->has('quotations.from');
            $json->has('quotations.last_page');
            $json->has('quotations.last_page_url');
            $json->has('quotations.links');
            $json->has('quotations.next_page_url');
            $json->has('quotations.path');
            $json->has('quotations.per_page');
            $json->has('quotations.prev_page_url');
            $json->has('quotations.to');
            $json->has('quotations.total');
        });
    }

    /**
     * Test View quotation
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Quotation\QuotationController::view()
     *     To see the method
     */
    public function test_view_quotation(): void
    {
        $user = $this->authenticate();

        $company = $user->owner->company;
        $customer = Customer::factory()->for($company)->create();

        $quotation = Quotation::factory()
            ->for($company)
            ->for($customer)
            ->createQuietly();

        $response = $this->getJson(urlWithParams('/api/dashboard/companies/quotations/view', [
            'quotation_id' => $quotation->id
        ]));

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('quotation');
            $json->has('quotation.id');
        });
    }

    /**
     * Test generate invoice of quotation
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Quotation\QuotationController::generateInvoice()
     *     To see the method
     */
    public function test_generate_invoice(): void
    {
        $user = $this->authenticate();

        $company = $user->owner->company;
        $customer = Customer::factory()->for($company)->create();

        $quotation = Quotation::factory()
            ->for($company)
            ->for($customer)
            ->createQuietly();

        $quotationItems = QuotationItem::factory()->for($quotation)->count(rand(1, 3))->create();

        $quotation->countWorksAmount();

        $quotation = $quotation->refresh();

        $response = $this->postJson('/api/dashboard/companies/quotations/generate_invoice', [
            'quotation_id' => $quotation->id
        ]);


        $response->assertCreated();

        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');

            $json->has('invoice');
            $json->has('invoice.id');
        });

        $content = $response->getOriginalContent();
        $invoice = $content['invoice'];
        $invoice = Invoice::find($invoice['id']);

        $this->assertEquals($invoice->customer_id, $quotation->customer_id);
        $this->assertEquals($invoice->potential_amount, $quotation->potential_amount);
        $this->assertEquals($invoice->discount_amount, $quotation->discount_amount);
        $this->assertEquals($invoice->total_amount, $quotation->total_amount);
        $this->assertEquals($invoice->company_id, $quotation->company_id);
        $this->assertEquals($invoice->items_count, $quotation->items_count);

        foreach ($quotation->items as $item) {
            $this->assertDatabaseHas((new InvoiceItem())->getTable(), [
                'invoice_id' => $invoice->id,
                'work_service_id' => $item->work_service_id,
            ]);
        }

    }

    /**
     * Test print quotation
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Quotation\QuotationController::print()
     *     To see the method
     */
    public function test_print_quotation(): void
    {
        $user = $this->authenticate();

        $company = $user->owner->company;
        $customer = Customer::factory()
            ->for($company)->create();

        $quotation = Quotation::factory()
            ->for($company)
            ->for($customer)
            ->createQuietly();

        $response = $this->postJson('/api/dashboard/companies/quotations/print', [
            'quotation_id' => $quotation->id
        ]);
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
            $json->has('quotation');
        });

        $this->assertDatabaseHas('quotations', [
            'id' => $quotation->id,
            'status' => QuotationStatus::Sent,
        ]);
    }

    /**
     * Test send quotation
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Quotation\QuotationController::send()
     *     To see the method
     */
    public function test_send_quotation(): void
    {
        Queue::fake();

        $user = $this->authenticate();

        $company = $user->owner->company;
        $customer = Customer::factory()->for($company)->create();

        $quotation = Quotation::factory()
            ->for($customer)
            ->for($company)
            ->drafted()
            ->createQuietly();


        $requestData = [
            'quotation_id' => $quotation->id,
            'customer_id' => $quotation->customer_id,
            'customer_address' => $quotation->customer_address,
            'number' => (string)$quotation->number,
            'date' => $quotation->date->format('Y-m-d'),
            'expiry_date' => $quotation->expiry_date->format('Y-m-d'),
            'note' => $quotation->note,
            'discount_amount' => $quotation->discount_amount,
            'potential_amount' => $quotation->potential_amount,
        ];
        foreach (WorkService::factory(3)->for($company)->create() as $workService) {
            $requestData['work_services'][] = [
                'work_service_id' => $workService->id,
                'amount' => rand(1, 5),
            ];
        }

        /**
         * Ensure the drafted quotation send case fulfilled
         */
        $response = $this->postJson(
            "/api/dashboard/companies/quotations/send",
            $requestData,
        );

        $response->assertSuccessful();
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
        });


        $this->assertDatabaseHas((new Quotation())->getTable(), [
            'id' => $quotation->id,
            'status' => QuotationStatus::Sent,
            'customer_id' => $requestData['customer_id'],
            'customer_address' => $requestData['customer_address'],
            'number' => $requestData['number'],
            'date' => $requestData['date'],
            'expiry_date' => $requestData['expiry_date'],
        ]);

        // Assert database quotation log record added
        $this->assertDatabaseHas('quotation_logs', [
            'quotation_id' => $quotation->id,
            'log_name' => 'updated',
        ]);

        $this->assertDatabaseHas('quotation_logs', [
            'quotation_id' => $quotation->id,
            'log_name' => 'status_changed',
        ]);

        // Assert queued job and mailable pushed correctly
        Queue::assertPushed(SendMail::class, function (SendMail $sendMailJob) use ($customer, $quotation) {
            if ($sendMailJob->destination !== $customer->email) {
                return false;
            }

            $mailable = $sendMailJob->mailable;
            if (!$mailable instanceof QuotationMail) {
                return false;
            }

            if ($mailable->quotation->id !== $quotation->id) {
                return false;
            }

            if ($mailable->quotation->customer_id !== $customer->id) {
                return false;
            }

            return true;
        });
    }

    /**
     * Ensure the case where quotation never been created and then
     * saved and sent directly to the customer.
     *
     * @test
     * @return void
     * @see \App\Http\Controllers\Api\Company\Quotation\QuotationController::send()
     *     To the tested controller method.
     */
    public function test_save_and_send_uncreated_quotation(): void
    {
        Queue::fake();

        $user = $this->authenticate();

        $company = $user->owner->company;
        $customer = Customer::factory()->for($company)->create();

        $requestData = [
            'customer_id' => $customer->id,
            'customer_address' => $this->faker->address,
            'number' => (string)rand(10000, 99999),
            'date' => now()->format('Y-m-d'),
            'expiry_date' => now()->addDays(3)->format('Y-m-d'),
            'discount_amount' => 10,
            'potential_amount' => rand(100, 500),
        ];
        foreach (WorkService::factory(3)->for($company)->create() as $workService) {
            $requestData['work_services'][] = [
                'work_service_id' => $workService->id,
                'amount' => rand(1, 5),
            ];
        }

        $response = $this->postJson(
            "/api/dashboard/companies/quotations/send",
            $requestData,
        );

        $response->assertSuccessful();
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
        });

        // Assert quotation database record created
        $this->assertDatabaseHas((new Quotation())->getTable(), [
            'status' => QuotationStatus::Sent,
            'customer_id' => $customer->id,
        ]);
        $quotation = $customer->quotations->first();

        // Assert database quotation log record added
        $this->assertDatabaseHas('quotation_logs', [
            'quotation_id' => $quotation->id,
            'log_name' => 'created',
        ]);
        $this->assertDatabaseHas('quotation_logs', [
            'quotation_id' => $quotation->id,
            'log_name' => 'updated', // Updated to sent
        ]);

        // Assert queued job and mailable pushed correctly
        Queue::assertPushed(SendMail::class, function (SendMail $sendMailJob) use ($customer, $quotation) {
            if ($sendMailJob->destination !== $customer->email) {
                return false;
            }

            $mailable = $sendMailJob->mailable;
            if (!$mailable instanceof QuotationMail) {
                return false;
            }

            if ($mailable->quotation->id !== $quotation->id) {
                return false;
            }

            if ($mailable->quotation->customer_id !== $customer->id) {
                return false;
            }

            return true;
        });
    }

    /**
     * Test nullify a Quotation
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Quotation\QuotationController::nullify()
     *     To see the method
     */
    public function test_nullify_quotation(): void
    {
        $user = $this->authenticate();

        $company = $user->owner->company;
        $customer = Customer::factory()->for($company)->create();

        $quotation = Quotation::factory()
            ->for($customer)
            ->for($company)
            ->sent()
            ->createQuietly();

        $response = $this->patchJson("/api/dashboard/companies/quotations/nullify", [
            'quotation_id' => $quotation->id
        ]);

        $response->assertStatus(200);

        $response->assertJson(function (AssertableJson $json) {
            $json->has("status");
            $json->has("message");
        });

        // Assert quotation database record created
        $this->assertDatabaseHas((new Quotation())->getTable(), [
            'id' => $quotation->id,
            'status' => QuotationStatus::Nullified
        ]);
    }

    /**
     * Test soft delete quotation
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Quotation\QuotationController::delete()
     *     To see the method
     */
    public function test_soft_delete_quotation(): void
    {
        $user = $this->authenticate();

        $company = $user->owner->company;
        $customer = Customer::factory()->for($company)->create();

        $quotation = Quotation::factory()
            ->for($customer)
            ->for($company)
            ->nullified()
            ->createQuietly();

        $response = $this->deleteJson("/api/dashboard/companies/quotations/delete", [
            'quotation_id' => $quotation->id
        ]);

        $this->assertSoftDeleted((new Quotation())->getTable(), ["id" => $quotation->id]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
        });
    }

    /**
     * Test force delete quotation
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Quotation\QuotationController::delete()
     *     To see the method
     */
    public function test_force_delete_quotation(): void
    {
        $user = $this->authenticate();

        $company = $user->owner->company;
        $customer = Customer::factory()->for($company)->create();

        $quotation = Quotation::factory()
            ->for($customer)
            ->for($company)
            ->softDeleted()
            ->createQuietly();


        $response = $this->deleteJson("/api/dashboard/companies/quotations/delete", [
            'force' => true,
            'quotation_id' => $quotation->id
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseMissing((new Quotation())->getTable(), ["id" => $quotation->id]);

        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
        });
    }

    /**
     * Test restore quotation
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Quotation\QuotationController::restore()
     *      to the the tested controller method
     */
    public function test_restore_quotation(): void
    {
        $user = $this->authenticate();

        $company = $user->owner->company;
        $customer = Customer::factory()->for($company)->create();

        $quotation = Quotation::factory()
            ->for($customer)
            ->for($company)
            ->drafted()
            ->createQuietly();

        // delete the quotation with softDelete
        $this->assertTrue(!!Quotation::where("id", $quotation->id)->delete());

        // make sure the quotation is soft deleted
        $this->assertSoftDeleted((new Quotation())->getTable(), ["id" => $quotation->id]);

        $response = $this->patchJson("/api/dashboard/companies/quotations/restore", [
            "quotation_id" => $quotation->id
        ]);

        // check the quotation restoration
        $this->assertDatabaseHas((new Quotation())->getTable(), ["id" => $quotation->id, "deleted_at" => null]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "quotation" => [
                "id",
            ],
            "message",
            "status"
        ]);
    }

    /**
     * Ensure the quotation save signed document is working.
     *
     * @test
     * @return void
     * @see \App\Http\Controllers\Api\Company\Quotation\QuotationController::saveSignedDoc()
     *      To the tested controller method.
     */
    public function test_save_signed_document(): void
    {
        $user = $this->authenticate();

        $company = $user->owner->company;
        $customer = Customer::factory()->for($company)->create();

        $quotation = Quotation::factory()
            ->for($customer)
            ->for($company)
            ->sent()
            ->createQuietly();

        $signedDocument = UploadedFile::fake()->create(
            $uploadedFileName = 'test_' . random_string(5) . '_signed_document.pdf',
            100,
            'application/pdf'
        );

        $url = '/api/dashboard/companies/quotations/signed_document/upload';
        $response = $this->postJson($url, [
            'quotation_id' => $quotation->id,
            'signed_document' => $signedDocument,
        ]);

        // Assert the request should be successful
        $response->assertSuccessful();
        $response->assertJson(['status' => 'success']);

        // Assert the needed changes
        $quotation->refresh();
        $this->assertTrue($quotation->isSigned());

        // Assert the signed document is uploaded
        $signedDocument = $quotation->getFirstMedia('signed_document');
        $this->assertEquals($uploadedFileName, $signedDocument->file_name);
    }

    /**
     * Ensure quotation logs feature works as expected.
     *
     * @test
     * @return void
     */
    public function test_quotation_logs(): void
    {
        $user = $this->authenticate();

        $company = $user->owner->company;
        $customer = Customer::factory()->for($company)->create();

        $quotation = Quotation::factory()
            ->for($customer)
            ->for($company)
            ->sent()
            ->createQuietly();
        $logs = QuotationLog::factory(5)
            ->create([
                'quotation_id' => $quotation->id,
                'actor_id' => $user->id,
            ]);

        $url = '/api/dashboard/companies/quotations/logs?quotation_id=' . $quotation->id;
        $response = $this->getJson($url);

        // Assert request successful
        $response->assertSuccessful();

        // Assert request contents
        $content = $response->getOriginalContent();
        $this->assertTrue(isset($content['logs']['data']));
        foreach ($content['logs']['data'] as $date => $logsInOneDay) {
            foreach ($logsInOneDay as $log) {
                $found = $logs->where('id', $log->id)->first();
                $this->assertNotNull($found);
                $this->assertEquals($user->id, $log->actor_id);
            }
        }
    }
}
