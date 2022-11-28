<?php

namespace App\Http\Requests\Company\Quotations;

use App\Enums\Quotation\QuotationStatus;
use App\Models\{Customer\Customer, Quotation\Quotation};
use App\Rules\FloatValue;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class DraftQuotationRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Customer target model container
     *
     * @var Customer|null
     */
    private ?Customer $customer = null;


    /**
     * Found Quotation model container
     *
     * @var Quotation|null
     */
    private ?Quotation $quotation = null;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function authorize(): bool
    {
        $quotation = null;
        if ($this->has('quotation_id')) {
            $quotation = $this->getQuotation();
        }
        return $this->user()->fresh()->can('draft-quotation', $quotation);
    }

    /**
     * Get Quotation based on supplied input
     *
     * @return ?Quotation
     */
    public function getQuotation(): ?Quotation
    {
        if ($this->quotation) {
            return $this->quotation;
        }
        $id = $this->input('quotation_id');
        return $this->quotation = Quotation::findOrFail($id);
    }

    /**
     * Get Customer based on supplied input
     *
     * @return Customer|null
     */
    public function getCustomer(): ?Customer
    {
        if ($this->customer) {
            return $this->customer;
        }
        $customerId = $this->input('customer_id');
        $this->customer = Customer::findOrFail($customerId);
        return $this->customer;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function rules(): array
    {
        $company = $this->getCompany();

        return [
            'quotation_id' => ['nullable', 'string', Rule::exists('quotations', 'id')->where('company_id', $company->id)],
            'customer_id' => ['required', 'string', Rule::exists('customers', 'id')->where('company_id', $company->id)],
            'customer_address' => ['nullable', 'string'],
            'number' => ['required', 'string'],
            'date' => ['nullable', 'date'],
            'expiry_date' => ['nullable', 'date', 'after_or_equal:date'],
            'note' => ['nullable', 'string'],
            'discount_amount' => ['nullable', new FloatValue(true)],
            'potential_amount' => ['nullable', new FloatValue(true)],
            'work_services' => ['nullable', 'array'],
            'work_services.*.work_service_id' => ['nullable', Rule::exists('work_services', 'id')->where('company_id', $company->id)],
            'work_services.*.amount' => ['nullable', 'numeric'],
        ];
    }

    /**
     * Get quotation data
     *
     * @return array[]
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function quotationData(): array
    {
        return [
            'quotation_data' => [
                'date' => $this->input('date'),
                'expiry_date' => $this->input('expiry_date'),
                'customer_id' => $this->input('customer_id'),
                'customer_address' => $this->input('customer_address'),
                'potential_amount' => $this->input('potential_amount'),
                'discount_amount' => $this->input('discount_amount'),
                'number' => $this->input('number'),
                'company_id' => $this->getCompany()->id,
                'status' => QuotationStatus::Drafted,
                'note' => $this->input('note'),
            ],
            'quotation_items' => $this->input('work_services', []),
        ];
    }

    /**
     * Format input payload before validation
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'expiry_date' => $this->input('expiry_date') ?
                carbon()->parse($this->input('expiry_date')) :
                carbon()->now()->addDays(3),
            'discount_amount' => floatval($this->input('discount_amount') ?: 0),
            'potential_amount' => floatval($this->input('potential_amount') ?: 0),
        ]);
    }
}
