<?php

namespace Tests\Feature\Dashboard\Companies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ User, Owner, Company, PaymentTerm, Invoice, Customer };

class PaymentTermTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Tested module base URL
     * 
     * @var string
     */
    private $baseUrl = '/api/dashboard/companies/invoices/payment_terms';

    /**
     * A view all invoice payment terms test.
     *
     * @return void
     */
    public function test_view_all_invoice_payment_terms()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $invoice = Invoice::factory()
            ->for($company)
            ->for(Customer::factory()->for($company)->create())
            ->has(PaymentTerm::factory()->count(rand(3, 5)))
            ->create();
        $url = $this->baseUrl . '?invoice_id=' . $invoice->id;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('payment_terms');
        });
    }

    /**
     * A store invoice payment term test.
     *
     * @return void
     */
    public function test_store_invoice_payment_term()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $invoice = Invoice::factory()
            ->for($company)
            ->for(Customer::factory()->for($company)->create())
            ->create();
        $url = $this->baseUrl . '/store';
        $response = $this->json('POST', $url, [
            'invoice_id' => $invoice->id,
            'term_name' => 'Example term name',
            'amount' => 1,
            'due_date' => now()->addDays(10)->toDateString(),
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A update invoice payment term test.
     *
     * @return void
     */
    public function test_update_invoice_payment_term()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl . '/update';
        $term = PaymentTerm::factory()
            ->for($company)
            ->for(
                Invoice::factory()
                    ->for($company)
                    ->for(Customer::factory()->for($company)->create())
                    ->create()
            )->unpaid()
            ->create();
        $response = $this->json('PATCH', $url, [
            'payment_term_id' => $term->id,
            'term_name' => 'Term name example',
            'amount' => $term->invoice->total_out_terms,
            'due_date' => now()->addDays(3)->toDateString(),
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A mark invoice payment term as paid test.
     *
     * @return void
     */
    public function test_mark_invoice_payment_term_as_paid()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl . '/mark_as_paid';
        $term = PaymentTerm::factory()
            ->for($company)
            ->for(
                Invoice::factory()
                    ->for($company)
                    ->for(Customer::factory()->for($company)->create())
                    ->create()
            )->unpaid()
            ->create();
        $response = $this->json('POST', $url, [
            'payment_term_id' => $term->id,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A cancel invoice payment term paid status test.
     *
     * @return void
     */
    public function test_cancel_invoice_payment_term_paid_status()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl . '/cancel_paid_status';
        $term = PaymentTerm::factory()
            ->for($company)
            ->for(
                Invoice::factory()
                    ->for($company)
                    ->for(Customer::factory()->for($company)->create())
                    ->create()
            )->unpaid()
            ->create();
        $response = $this->json('POST', $url, [
            'payment_term_id' => $term->id,
            'reason' => 'Reason example',
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A forward invoice payment term to debt collector test.
     *
     * @return void
     */
    public function test_forward_payment_term_to_debt_collector()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl . '/forward_to_debt_collector';
        $term = PaymentTerm::factory()
            ->for($company)
            ->for(
                Invoice::factory()
                    ->for($company)
                    ->for(Customer::factory()->for($company)->create())
                    ->create()
            )->unpaid()
            ->create();
        $response = $this->json('POST', $url, ['payment_term_id' => $term->id]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A delete invoice payment term.
     *
     * @return void
     */
    public function test_delete_invoice_payment_term()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $url = $this->baseUrl . '/delete';
        $term = PaymentTerm::factory()
            ->for($company)
            ->for(
                Invoice::factory()
                    ->for($company)
                    ->for(Customer::factory()->for($company)->create())
                    ->create()
            )->unpaid()
            ->create();;
        $response = $this->json('DELETE', $url, [
            'payment_term_id' => $term->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }
}
