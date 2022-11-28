<?php

namespace Tests\Integration\Dashboard\Company\Invoice;

use App\Enums\Invoice\InvoiceStatus;
use App\Models\Customer\Customer;
use App\Models\Invoice\Invoice;
use App\Models\User\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvoiceStatusActionTest extends TestCase
{
    use WithFaker;


    const BASE_URL = '/api/dashboard/companies/invoices';

    /**
     * Test update status on draft invoice.
     *
     * @return void
     */
    public function test_successfully_update_invoice_status_on_draft(): void
    {
        $this->actingAs($user = User::factory()->owner()->create());

        $company = $user->owner->company;
        $customer = Customer::factory()->for($company)->create();

        $invoice = Invoice::factory()
            ->for($company)
            ->for($customer)
            ->drafted()
            ->create();

        $response = $this->patchJson(self::BASE_URL . '/change_status', [
            'invoice_id' => $invoice->id,
            'status' => InvoiceStatus::Sent
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => InvoiceStatus::Sent
        ]);
    }

    /**
     * Test update status on draft invoice.
     *
     * @return void
     */
    public function test_failed_update_invoice_status_on_draft(): void
    {
        $this->actingAs($user = User::factory()->owner()->create());

        $company = $user->owner->company;
        $customer = Customer::factory()->for($company)->create();

        $invoice = Invoice::factory()
            ->for($company)
            ->for($customer)
            ->drafted()
            ->create();

        $response = $this->patchJson(self::BASE_URL . '/change_status', [
            'invoice_id' => $invoice->id,
            'status' => InvoiceStatus::Paid
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => InvoiceStatus::Drafted
        ]);
    }

    /**
     * Test successfully update status on sent invoice.
     *
     * @return void
     */
    public function test_successfully_update_status_on_sent(): void
    {
        $this->actingAs($user = User::factory()->owner()->create());

        $company = $user->owner->company;
        $customer = Customer::factory()->for($company)->create();
        $invoice = Invoice::factory()
            ->for($company)
            ->for($customer)
            ->sent()
            ->create();

        $response = $this->patchJson(self::BASE_URL . '/change_status', [
            'invoice_id' => $invoice->id,
            'status' => InvoiceStatus::Paid
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => InvoiceStatus::Paid
        ]);
    }

    /**
     * Test failed update status on sent invoice.
     *
     * @return void
     */
    public function test_failed_update_status_on_sent(): void
    {
        $this->actingAs($user = User::factory()->owner()->create());

        $company = $user->owner->company;
        $customer = Customer::factory()->for($company)->create();
        $invoice = Invoice::factory()
            ->for($company)
            ->for($customer)
            ->sent()
            ->create();

        $response = $this->patchJson(self::BASE_URL . '/change_status', [
            'invoice_id' => $invoice->id,
            'status' => InvoiceStatus::FirstReminderSent
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => InvoiceStatus::Sent
        ]);
    }

    /**
     * Test successfully update status on overdue invoice.
     *
     * @return void
     */
    public function test_successfully_update_status_on_overdue(): void
    {
        $this->actingAs($user = User::factory()->owner()->create());

        $company = $user->owner->company;
        $customer = Customer::factory()->for($company)->create();
        $invoice = Invoice::factory()
            ->for($company)
            ->for($customer)
            ->overdue()
            ->create();

        $response = $this->patchJson(self::BASE_URL . '/change_status', [
            'invoice_id' => $invoice->id,
            'status' => InvoiceStatus::Paid
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => InvoiceStatus::Paid
        ]);
    }

    /**
     * Test failed update status on overdue invoice.
     *
     * @return void
     */
    public function test_failed_update_status_on_overdue(): void
    {
        $this->actingAs($user = User::factory()->owner()->create());

        $company = $user->owner->company;
        $customer = Customer::factory()->for($company)->create();
        $invoice = Invoice::factory()
            ->for($company)
            ->for($customer)
            ->overdue()
            ->create();

        $response = $this->patchJson(self::BASE_URL . '/change_status', [
            'invoice_id' => $invoice->id,
            'status' => InvoiceStatus::SecondReminderSent
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => InvoiceStatus::PaymentOverdue
        ]);
    }
}
