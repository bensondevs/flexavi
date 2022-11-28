<?php

namespace Tests\Integration\Dashboard\Company\Quotation;

use App\Enums\Quotation\QuotationStatus;
use App\Jobs\SendMail;
use App\Mail\Quotation\QuotationMail;
use App\Models\Customer\Customer;
use App\Models\Quotation\Quotation;
use App\Models\User\User;
use Illuminate\Foundation\Testing\{WithFaker};
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class DeleteQuotationTest extends TestCase
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
     * Test delete quotation before quotation nullify
     *
     * @return void
     */
    public function test_delete_quotation_before_quotation_nullify(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;
        $customer = Customer::factory()->for($company)->create();
        $quotation = Quotation::factory()->for($company)->for($customer)->drafted()->createQuietly();

        $response = $this->deleteJson('/api/dashboard/companies/quotations/delete', [
            'quotation_id' => $quotation->id,
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseHas((new Quotation())->getTable(), [
            'id' => $quotation->id,
            'status' => QuotationStatus::Created,
        ]);
    }

    /**
     * Test delete quotation after quotation nullify
     *
     * @return void
     */
    public function test_delete_quotation_after_quotation_nullify(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;
        $customer = Customer::factory()->for($company)->create();
        $quotation = Quotation::factory()->for($company)->for($customer)->nullified()->createQuietly();

        $response = $this->deleteJson('/api/dashboard/companies/quotations/delete', [
            'quotation_id' => $quotation->id,
        ]);

        $response->assertStatus(200);

        $this->assertSoftDeleted((new Quotation())->getTable(), [
            'id' => $quotation->id,
        ]);
    }


    /**
     * Test force delete quotation on trash
     *
     * @return void
     */
    public function test_force_delete_quotation_on_trash(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );
        $company = $user->owner->company;
        $customer = Customer::factory()->for($company)->create();
        $quotation = Quotation::factory()->for($company)->for($customer)->softDeleted()->createQuietly();

        $response = $this->deleteJson('/api/dashboard/companies/quotations/delete', [
            'quotation_id' => $quotation->id,
            'force' => true,
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
        });

        $this->assertDatabaseMissing((new Quotation())->getTable(), [
            'id' => $quotation->id,
        ]);

    }
}
