<?php

namespace Tests\Feature\Dashboard\Company\Customer;

use App\Http\Resources\Customer\CustomerResource;
use App\Models\Customer\Customer;
use App\Traits\FeatureTestUsables;
use Database\Factories\AddressFactory;
use Database\Factories\AppointmentFactory;
use Database\Factories\CustomerFactory;
use Database\Factories\CustomerNoteFactory;
use Database\Factories\InvoiceFactory;
use Database\Factories\QuotationFactory;
use Database\Factories\WorkContractFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\Company\Customer\CustomerController::view()
 *      To the tested controller class.
 */
class ViewCustomerTest extends TestCase
{
    use WithFaker;
    use FeatureTestUsables;

    /**
     * Module base URL container constant.
     *
     * @const
     */
    public const MODULE_BASE_URL = '/api/dashboard/companies/customers';

    /**
     * Test view a company customer with relations
     *
     * @test
     * @return void
     */
    public function test_view_customer_with_relations(): void
    {
        $user = $this->authenticateAsOwner();
        $company = $user->company;

        $customer = CustomerFactory::new()->for($company)
            ->create();
        $this->assertNotNull($customer->fresh()->company);

        // prepare address for customer (create 2 addresses)
        $addresses = AddressFactory::new()->customer($customer)->count(2)
            ->create();
        $this->assertNotNull($customer->fresh()->address);
        $this->assertNotNull($customer->fresh()->addresses);

        // prepare appointment for customer
        $appointment = AppointmentFactory::new()->for($company)->for($customer)
            ->createQuietly(['id' => generateUuid()]); // create quietly to prevent error memory limit
        $this->assertNotNull($customer->fresh()->appointments);

        // prepare quotation for customer
        $quotation = QuotationFactory::new()->for($company)->for($customer)->for($appointment)
            ->create();
        $this->assertNotNull($customer->fresh()->quotations);

        // prepare invoice for customer
        $invoice = InvoiceFactory::new()->for($company)->for($customer)
            ->create();
        $this->assertNotNull($customer->fresh()->invoices);

        $customerNote = CustomerNoteFactory::new()->for($customer)->create();
        $this->assertNotNull($customer->fresh()->notes);

        // prepare work_contract for customer
        $workContract = WorkContractFactory::new()->for($company)->for($customer)
            ->create();
        $this->assertNotNull($customer->fresh()->workContracts);

        $url = urlWithParams(self::MODULE_BASE_URL . '/view', [
                'id' => $customer->id,
                'with_address' => true,
                'with_addresses' => true,
                'with_company' => true,
                'with_quotations' => true,
                'with_appointments' => true,
                'with_invoices' => true,
                'with_notes' => true,
                'with_workContracts' => true,
            ]);

        $response = $this->getJson($url);

        $response->assertOk();
        $this->assertInstanceReturnedInResponse($response, 'customer', CustomerResource::class, [
            // check if addresses relation loaded
            'address',
            'address.0.id',
            'addresses',
            'addresses.0.id',
            'addresses.1.id',

            // check if company relation loaded
            'company',
            'company.id',

            // check if quotations relation loaded
            'quotations',
            'quotations.0.id',

            // check if appointments relation loaded
            'appointments',
            'appointments.0.id',

            // check if invoices relation loaded
            'invoices',
            'invoices.0.id',

            // check if notes relation loaded
            'notes',
            'notes.0.id',

            // check if work_contracts relation loaded
            'work_contracts',
            'work_contracts.0.id',
        ]);
    }

    /**
     * Test view a company customer without relations
     *
     * @test
     * @return void
     */
    public function test_view_customer_without_relations(): void
    {
        $user = $this->authenticateAsOwner();
        $company = $user->company;

        $customer = CustomerFactory::new()->for($company)
            ->create();
        $this->assertNotNull($customer->fresh()->company);

        // prepare address for customer (create 2 addresses)
        $addresses = AddressFactory::new()->customer($customer)->count(2)
            ->create();
        $this->assertNotNull($customer->fresh()->address);
        $this->assertNotNull($customer->fresh()->addresses);

        // prepare appointment for customer
        $appointment = AppointmentFactory::new()->for($company)->for($customer)
            ->createQuietly(['id' => generateUuid()]); // create quietly to prevent error memory limit
        $this->assertNotNull($customer->fresh()->appointments);

        // prepare quotation for customer
        $quotation = QuotationFactory::new()->for($company)->for($customer)->for($appointment)
            ->create();
        $this->assertNotNull($customer->fresh()->quotations);

        // prepare invoice for customer
        $invoice = InvoiceFactory::new()->for($company)->for($customer)
            ->create();
        $this->assertNotNull($customer->fresh()->invoices);

        $customerNote = CustomerNoteFactory::new()->for($customer)->create();
        $this->assertNotNull($customer->fresh()->notes);

        // prepare work_contract for customer
        $workContract = WorkContractFactory::new()->for($company)->for($customer)
            ->create();
        $this->assertNotNull($customer->fresh()->workContracts);

        $url = urlWithParams(self::MODULE_BASE_URL . '/view', [
                'id' => $customer->id,
                'with_address' => false,
                'with_addresses' => false,
                'with_company' => false,
                'with_quotations' => false,
                'with_appointments' => false,
                'with_invoices' => false,
                'with_notes' => false,
                'with_workContracts' => false,
            ]);

        $response = $this->getJson($url);

        $response->dd();

        $response->assertOk();
        $this->assertInstanceReturnedInResponse($response, 'customer', CustomerResource::class);
    }
}
