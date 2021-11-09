<?php

namespace Tests\Feature\Dashboard\Companies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ 
    Owner, 
    Company, 
    Customer, 
    Quotation, 
    QuotationAttachment 
};

use App\Enums\Quotation\{ 
    QuotationType as Type, 
    QuotationPaymentMethod as PaymentMethod 
};

class QuotationTest extends TestCase
{
    use DatabaseTransactions;

    private $baseUrl = '/api/dashboard/companies/quotations';

    /**
     * Load all quotations test.
     *
     * @return void
     */
    public function test_view_all_quotations()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = $this->baseUrl;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('quotations');
        });
    }

    /**
     * Load all trashed quotations test.
     *
     * @return void
     */
    public function test_trashed_quotations()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = $this->baseUrl . '/trasheds';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('quotations');
        });
    }

    /**
     * Store quotation test.
     *
     * @return void
     */
    public function test_store_quotation()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $customer = Customer::factory()->for($company)->create();

        $quotationData = [
            'customer_id' => $customer->id,
            'type' => rand(Type::Leakage, Type::Renewal),
            'quotation_number' => 112233445566,
            'quotation_date' => '2021-09-22',
            'contact_person' => 'Test contact person',
            'address' => 'Address example test',
            'zip_code' => 111111,
            'phone_number' => '08829731233',
            'damage_causes' => '[1, 2, 3]',
            'quotation_description' => 'The description example',
            'expiry_date' => '2021-07-01',
            'vat_percentage' => 21,
            'discount_amount' => 125,
            'payment_method' => rand(PaymentMethod::Cash, PaymentMethod::BankTransfer),
        ];
        $url = $this->baseUrl . '/store';
        $response = $this->json('POST', $url, $quotationData);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
        });
    }

    /**
     * View quotation test.
     *
     * @return void
     */
    public function test_view_quotation()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $quotation = Quotation::factory()->for($company)->create();

        $url = $this->baseUrl . '/view?id=' . $quotation->id;
        $response = $this->json('GET', $url);


        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('quotation');
        });
    }

    /**
     * View quotation attachments test.
     *
     * @return void
     */
    public function test_view_quotation_attachments()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $quotation = Quotation::factory()->for($company)->create();

        $url = $this->baseUrl . '/attachments?quotation_id=' . $quotation->id;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('attachments');
        });
    }

    /**
     * Add quotation attachment test.
     *
     * @return void
     */
    public function test_add_quotation_attachment()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $quotation = Quotation::factory()->for($company)->create();

        $url = $this->baseUrl . '/attachments/add';
        $response = $this->json('POST', $url, [
            'quotation_id' => $quotation->id,
            'name' => 'Example attachment',
            'description' => 'Example description',
            'attachment' => file_get_contents(base_path() . '/tests/Resources/image_base64_example.txt'),
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Remove quotation attachment test.
     *
     * @return void
     */
    public function test_remove_quotation_attachment()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $quotation = Quotation::factory()->for($company)->create();
        $attachment = QuotationAttachment::factory()->for($quotation)->create();

        $url = $this->baseUrl . '/attachments/remove';
        $response = $this->json('DELETE', $url, ['id' => $attachment->id]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Send quotation test.
     *
     * @return void
     */
    public function test_send_quotation()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $quotation = Quotation::factory()->for($company)->create();

        $sendData = [
            'quotation_id' => $quotation->id,
            'destination' => 'send@destination.com',
            'text' => 'This is text example',
        ];
        $url = $this->baseUrl . '/send';
        $response = $this->json('POST', $url, $sendData);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Print quotation test.
     *
     * @return void
     */
    public function test_print_quotation()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $quotation = Quotation::factory()->for($company)->create();

        $url = $this->baseUrl . '/print';
        $response = $this->json('POST', $url, ['id' => $quotation->id]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Revise quotation test.
     *
     * @return void
     */
    public function test_revise_quotation()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $quotation = Quotation::factory()->for($company)->draft()->create();

        $revisionData = [
            'quotation_id' => $quotation->id,
            'discount_amount' => 18.90,
            'payment_method' => 1,
        ];
        $url = $this->baseUrl . '/revise';
        $response = $this->json('POST', $url, $revisionData);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Cancel quotation test.
     *
     * @return void
     */
    public function test_cancel_quotation()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $quotation = Quotation::factory()->for($company)->create();

        $cancelData = [
            'quotation_id' => $quotation->id,
            'cancellation_reason' => 'Cancel reason data example',
        ];
        $url = $this->baseUrl . '/cancel';
        $response = $this->json('POST', $url, $cancelData);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Honor quotation test.
     *
     * @return void
     */
    public function test_honor_quotation()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $quotation = Quotation::factory()->for($company)->create();

        $honorData = [
            'id' => $quotation->id,
            'discount_amount' => 100,
        ];
        $url = $this->baseUrl . '/honor';
        $response = $this->json('POST', $url, $honorData);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Generate quotation invoice test.
     *
     * @return void
     */
    public function test_generate_invoice_from_quotation()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $quotation = Quotation::factory()->for($company)->create();

        $url = $this->baseUrl . '/generate_invoice';
        $response = $this->json('POST', $url, [
            'quotation_id' => $quotation->id,
            'payment_method' => 2,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
            $json->has('invoice.quotation');
        });
    }

    /**
     * Update quotation test.
     *
     * @return void
     */
    public function test_update_quotation()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $quotation = Quotation::factory()->for($company)->create();

        $quotationData = [
            'id' => $quotation->id,
            'customer_id' => $quotation->customer_id,
            'type' => rand(Type::Leakage, Type::Renewal),
            'quotation_number' => '112233445566',
            'quotation_date' => '2021-09-22',
            'contact_person' => 'Test contact person',
            'address' => 'Address example test',
            'zip_code' => '111111',
            'phone_number' => '08829731233',
            'damage_causes' => '[1, 2, 3]',
            'quotation_description' => 'The description example',
            'expiry_date' => '2021-07-01',
            'vat_percentage' => 21,
            'discount_amount' => 125,
            'payment_method' => rand(PaymentMethod::Cash, PaymentMethod::BankTransfer),
        ];
        $url = $this->baseUrl . '/update';
        $response = $this->json('PATCH', $url, $quotationData);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
        });
    }

    /**
     * Delete quotation test.
     *
     * @return void
     */
    public function test_delete_quotation()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $quotation = Quotation::factory()->for($company)->create();

        $quotationData = [
            'quotation_id' => $quotation->id,
        ];
        $url = $this->baseUrl . '/delete';
        $response = $this->json('DELETE', $url, $quotationData);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }
}
