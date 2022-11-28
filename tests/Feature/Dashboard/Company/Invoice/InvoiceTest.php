<?php

namespace Tests\Feature\Dashboard\Company\Invoice;

use App\Enums\Invoice\InvoicePaymentMethod;
use App\Enums\Invoice\InvoiceStatus;
use App\Jobs\SendMail;
use App\Mail\Invoice\SendInvoice;
use App\Models\Customer\Customer;
use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceLog;
use App\Models\WorkService\WorkService;
use App\Traits\FeatureTestUsables;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\Company\Invoice\InvoiceController
 *      To see the controller class
 */
class InvoiceTest extends TestCase
{
    use WithFaker, FeatureTestUsables;

    /**
     * Test populate company invoices
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Invoice\InvoiceController::companyInvoices()
     *     To see the method
     */
    public function test_populate_company_invoices(): void
    {
        $user = $this->authenticateAsOwner();
        $company = $user->owner->company;

        Invoice::factory()
            ->count(5)
            ->for($company)
            ->create();

        $response = $this->getJson(
            '/api/dashboard/companies/invoices'
        );

        $response->assertStatus(200);
        $this->assertResponseAttributeIsPaginationInstance(
            $response,
            'invoices',
        );
    }

    /**
     * Test populate company trashed invoices
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Invoice\InvoiceController::companyTrashedInvoices()
     *      To see the method
     */
    public function test_populate_company_trashed_invoices(): void
    {
        $user = $this->authenticateAsOwner();

        $company = $user->owner->company;

        Invoice::factory()
            ->count(5)
            ->for($company)
            ->softDeleted()
            ->create();

        $response = $this->getJson(
            '/api/dashboard/companies/invoices/trasheds'
        );

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('invoices');
            $json->whereType('invoices.data', 'array');

            // pagination meta
            $json->has('invoices.current_page');
            $json->has('invoices.first_page_url');
            $json->has('invoices.from');
            $json->has('invoices.last_page');
            $json->has('invoices.last_page_url');
            $json->has('invoices.links');
            $json->has('invoices.next_page_url');
            $json->has('invoices.path');
            $json->has('invoices.per_page');
            $json->has('invoices.prev_page_url');
            $json->has('invoices.to');
            $json->has('invoices.total');
        });
    }

    /**
     * Test view company invoice
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Invoice\InvoiceController::view()
     *     To see the method
     */
    public function test_view_invoice(): void
    {
        $user = $this->authenticateAsOwner();

        $company = $user->owner->company;

        $customer = Customer::factory()
            ->for($company)
            ->create();

        $invoice = Invoice::factory()
            ->for($customer)
            ->for($company)
            ->create();


        $response = $this->getJson(urlWithParams('/api/dashboard/companies/invoices/view', [
            'invoice_id' => $invoice->id
        ]));

        $response->assertStatus(200);

        $response->assertJson(function (AssertableJson $json) {
            $json->has('invoice');
            $json->has('invoice.id');
        });
    }

    /**
     * Test store company invoice
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Invoice\InvoiceController::draft()
     *    To see the method
     */
    public function test_store_draft_invoice(): void
    {
        $user = $this->authenticateAsOwner();

        $company = $user->owner->company;

        $customer = Customer::factory()->for($company)->create();

        $workServices = WorkService::factory()->count(5)->for($company)->create();

        $input = [
            'company_id' => $user->owner->company->id,
            'customer_id' => $customer->id,
            'customer_address' => $this->faker->address,
            'payment_method' => InvoicePaymentMethod::getRandomValue(),
            'date' => now()->toDateString(),
            'due_date' => now()->addDays(rand(1, 30))->toDateString(),
            'note' => $this->faker->sentence . randomString(10),
            'discount_amount' => rand(1, 30),
        ];

        foreach ($workServices as $workService) {
            $input['items'][] = [
                'work_service_id' => $workService->id,
                'amount' => rand(1, 10),
            ];
        }

        $response = $this->postJson('/api/dashboard/companies/invoices/draft', $input);
        $response->assertStatus(201);

        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
        });

        $this->assertDatabaseHas((new Invoice())->getTable(), [
            'customer_id' => $customer->id,
            'company_id' => $company->id,
            'note' => $input['note'],
            'status' => InvoiceStatus::Drafted
        ]);
    }

    /**
     * Test update company invoice
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Invoice\InvoiceController::draft()
     *    To see the method
     */
    public function test_update_draft_invoice(): void
    {
        $user = $this->authenticateAsOwner();

        $company = $user->owner->company;

        $customer = Customer::factory()->for($company)->create();

        $invoice = Invoice::factory()->for($company)->for($customer)->drafted()->create();

        $workServices = WorkService::factory()->count(5)->for($company)->create();

        $input = [
            'invoice_id' => $invoice->id,
            'company_id' => $user->owner->company->id,
            'customer_id' => $customer->id,
            'customer_address' => $this->faker->address,
            'payment_method' => InvoicePaymentMethod::getRandomValue(),
            'date' => now()->toDateString(),
            'due_date' => now()->addDays(rand(1, 30))->toDateString(),
            'note' => $this->faker->sentence . randomString(10),
            'discount_amount' => rand(1, 30),
        ];

        foreach ($workServices as $workService) {
            $input['items'][] = [
                'work_service_id' => $workService->id,
                'amount' => rand(1, 10),
            ];
        }

        $response = $this->postJson('/api/dashboard/companies/invoices/draft', $input);
        $response->assertStatus(201);

        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
        });

        $this->assertDatabaseHas((new Invoice())->getTable(), [
            'id' => $invoice->id,
            'status' => InvoiceStatus::Drafted
        ]);
    }

    /**
     * Test store company invoice
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Invoice\InvoiceController::draft()
     *    To see the method
     */
    public function test_store_send_invoice(): void
    {
        Queue::fake();
        Mail::fake();
        $user = $this->authenticateAsOwner();

        $company = $user->owner->company;

        $customer = Customer::factory()->for($company)->create();

        $workServices = WorkService::factory()->count(5)->for($company)->create();

        $input = [
            'company_id' => $user->owner->company->id,
            'customer_id' => $customer->id,
            'customer_address' => $this->faker->address,
            'payment_method' => InvoicePaymentMethod::getRandomValue(),
            'date' => now()->toDateString(),
            'due_date' => now()->addDays(rand(1, 30))->toDateString(),
            'note' => $this->faker->sentence . randomString(10),
            'discount_amount' => rand(1, 30),
        ];

        foreach ($workServices as $workService) {
            $input['items'][] = [
                'work_service_id' => $workService->id,
                'amount' => rand(1, 10),
            ];
        }

        $response = $this->postJson('/api/dashboard/companies/invoices/send', $input);

        $response->assertStatus(201);

        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
        });

        $this->assertDatabaseHas((new Invoice())->getTable(), [
            'customer_id' => $customer->id,
            'company_id' => $company->id,
            'note' => $input['note'],
            'status' => InvoiceStatus::Sent
        ]);


        Queue::assertPushed(SendMail::class, function ($job) use ($customer) {
            $this->assertInstanceOf(SendInvoice::class, $job->mailable);
            return ($job->destination == $customer->email);
        });
    }

    /**
     * Test permanently delete company invoice
     *
     * @return void
     */
    public function test_permanently_delete_invoice(): void
    {
        $user = $this->authenticateAsOwner();

        $customer = Customer::factory()
            ->for($user->owner->company)
            ->create();

        $invoice = Invoice::factory()
            ->for($customer)
            ->for($user->owner->company)
            ->softDeleted()
            ->create();

        $response = $this->deleteJson('/api/dashboard/companies/invoices/delete', [
            'invoice_id' => $invoice->id,
            'force' => true
        ]);

        $response->assertStatus(200);

        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
        });

        $this->assertDatabaseMissing((new Invoice())->getTable(), [
            'id' => $invoice->id
        ]);

    }

    /**
     * Test restore company invoice
     *
     * @return void
     */
    public function test_restore_invoice(): void
    {
        $user = $this->authenticateAsOwner();
        $customer = Customer::factory()
            ->for($user->owner->company)
            ->create();
        $invoice = Invoice::factory()
            ->for($customer)
            ->for($user->owner->company)
            ->create();

        $invoice->delete();

        $response = $this->patchJson('/api/dashboard/companies/invoices/restore', [
            'invoice_id' => $invoice->id,
        ]);

        $this->assertDatabaseHas((new Invoice())->getTable(), [
            'id' => $invoice->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('invoice');
            $json->has('invoice.id');
            // status meta
            $json->where('status', 'success');
            $json->has('message');
        });
    }

    /**
     * Test print company invoice
     *
     * @return void
     */
    public function test_print_invoice(): void
    {
        $user = $this->authenticateAsOwner();
        $customer = Customer::factory()
            ->for($user->owner->company)
            ->create();

        $invoice = Invoice::factory()
            ->for($customer)
            ->for($user->owner->company)
            ->drafted()
            ->create();

        $response = $this->postJson('/api/dashboard/companies/invoices/print', [
            'invoice_id' => $invoice->id,
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas((new Invoice())->getTable(), [
            'id' => $invoice->id,
            'status' => InvoiceStatus::Sent,
        ]);

        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
        });
    }

    /**
     * Test delete company invoice
     *
     * @return void
     */
    public function test_delete_invoice(): void
    {
        $user = $this->authenticateAsOwner();
        $customer = Customer::factory()
            ->for($user->owner->company)
            ->create();
        $invoice = Invoice::factory()
            ->for($customer)
            ->for($user->owner->company)
            ->create();

        $response = $this->deleteJson('/api/dashboard/companies/invoices/delete', [
            'invoice_id' => $invoice->id
        ]);

        $this->assertSoftDeleted((new Invoice())->getTable(), [
            'id' => $invoice->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            // status meta
            $json->where('status', 'success');
            $json->has('message');
        });
    }

    /**
     * Test delete company invoice
     *
     * @return void
     */
    public function test_populate_invoice_logs(): void
    {
        $user = $this->authenticateAsOwner();
        $customer = Customer::factory()
            ->for($user->owner->company)
            ->create();

        $invoice = Invoice::factory()
            ->for($customer)
            ->for($user->owner->company)
            ->create();

        InvoiceLog::factory()->for($invoice)->count(5)->create();

        $response = $this->getJson(urlWithParams('/api/dashboard/companies/invoices/logs', ['invoice_id' => $invoice->id]));
        $response->assertSuccessful();
    }
}
