<?php

namespace Tests\Feature\Dashboard\Companies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

use App\Models\Owner;
use App\Models\Customer;
use App\Models\Quotation;

use App\Enums\Quotation\QuotationType;
use App\Enums\Quotation\QuotationPaymentMethod;

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = $this->baseUrl;
        $response = $this->withHeaders($headers)->get($url);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = $this->baseUrl . '/trasheds';
        $response = $this->withHeaders($headers)->get($url);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $customer = Customer::where('company_id', $owner->company_id)->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $quotationData = [
            'customer_id' => $customer->id,
            'type' => rand(QuotationType::Leakage, QuotationType::Renewal),
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
            'payment_method' => rand(QuotationPaymentMethod::Cash, QuotationPaymentMethod::BankTransfer),
        ];
        $url = $this->baseUrl . '/store';
        $response = $this->withHeaders($headers)->post($url, $quotationData);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $quotation = Quotation::where('company_id', $owner->company_id)->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = $this->baseUrl . '/view?id=' . $quotation->id;
        $response = $this->withHeaders($headers)->get($url);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $quotation = Quotation::where('company_id', $owner->company_id)->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = $this->baseUrl . '/attachments?quotation_id=' . $quotation->id;
        $response = $this->withHeaders($headers)->get($url);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $quotation = Quotation::where('company_id', $owner->company_id)->first();
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $attachmentData = [
            'quotation_id' => $quotation->id,
            'name' => 'Example attachment',
            'description' => 'Example description',
            'attachment' => file_get_contents(base_path() . '/tests/Resources/image_base64_example.txt'),
        ];
        $url = $this->baseUrl . '/attachments/add';
        $response = $this->withHeaders($headers)->post($url, $attachmentData);

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
        $quotation = Quotation::whereHas('attachments')->first();
        $attachment = $quotation->attachments()->first();
        $owner = Owner::whereHas('user')
            ->where('company_id', $quotation->company_id)
            ->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = $this->baseUrl . '/attachments/remove';
        $response = $this->withHeaders($headers)->delete($url, ['id' => $attachment->id]);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $quotation = Quotation::where('company_id', $owner->company_id)->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $sendData = [
            'quotation_id' => $quotation->id,
            'destination' => 'send@destination.com',
            'text' => 'This is text example',
        ];
        $url = $this->baseUrl . '/send';
        $response = $this->withHeaders($headers)->post($url, $sendData);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $quotation = Quotation::where('company_id', $owner->company_id)->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = $this->baseUrl . '/print';
        $response = $this->withHeaders($headers)->post($url, ['id' => $quotation->id]);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $quotation = Quotation::where('company_id', $owner->company_id)->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $revisionData = [
            'quotation_id' => $quotation->id,
            'discount_amount' => 18.90,
            'payment_method' => 1,
        ];
        $url = $this->baseUrl . '/revise';
        $response = $this->withHeaders($headers)->post($url, $revisionData);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $quotation = Quotation::where('company_id', $owner->company_id)->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $cancelData = [
            'quotation_id' => $quotation->id,
            'cancellation_reason' => 'Cancel reason data example',
        ];
        $url = $this->baseUrl . '/cancel';
        $response = $this->withHeaders($headers)->post($url, $cancelData);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $quotation = Quotation::where('company_id', $owner->company_id)->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $honorData = [
            'id' => $quotation->id,
            'discount_amount' => 100,
        ];
        $url = $this->baseUrl . '/honor';
        $response = $this->withHeaders($headers)->post($url, $honorData);

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
    public function test_generate_invoice_quotation()
    {
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $quotation = Quotation::where('company_id', $owner->company_id)->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = $this->baseUrl . '/generate_invoice';
        $response = $this->withHeaders($headers)->post($url, [
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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $quotation = Quotation::where('company_id', $owner->company_id)->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $quotationData = [
            'id' => $quotation->id,
            'customer_id' => $quotation->customer_id,
            'type' => rand(QuotationType::Leakage, QuotationType::Renewal),
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
            'payment_method' => rand(QuotationPaymentMethod::Cash, QuotationPaymentMethod::BankTransfer),
        ];
        $url = $this->baseUrl . '/update';
        $response = $this->withHeaders($headers)->patch($url, $quotationData);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $quotation = Quotation::where('company_id', $owner->company_id)->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $quotationData = [
            'quotation_id' => $quotation->id,
        ];
        $url = $this->baseUrl . '/delete';
        $response = $this->withHeaders($headers)->delete($url, $quotationData);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }
}
