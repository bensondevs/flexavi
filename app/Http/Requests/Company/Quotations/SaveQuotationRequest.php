<?php

namespace App\Http\Requests\Company\Quotations;

use App\Enums\Quotation\QuotationStatus;
use App\Models\{Customer\Customer};
use App\Rules\FloatValue;
use App\Rules\Helpers\Media;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SaveQuotationRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Customer target model container
     *
     * @var Customer|null
     */
    private ?Customer $customer = null;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $customer = $this->getCustomer();
        return $this->user()->fresh()->can('create-quotation', $customer);
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
            'customer_id' => ['required', 'string', Rule::exists('customers', 'id')],
            'customer_address' => ['required', 'string'],
            'number' => ['required', 'string'],
            'date' => ['required', 'date'],
            'expiry_date' => ['required', 'date', 'after_or_equal:date'],
            'note' => ['nullable', 'string'],
            'vat_percentage' => ['nullable', new FloatValue(true)],
            'discount_amount' => ['nullable', new FloatValue(true)],
            'potential_amount' => ['nullable', new FloatValue(true)],
            'signature' => [
                'required',
                'image',
                'max:' . Media::MAX_IMAGE_SIZE,
                'mimes:' . Media::imageExtensions(),
            ],
            'work_services' => ['required', 'array'],
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
                'vat_percentage' => $this->input('vat_percentage'),
                'discount_amount' => $this->input('discount_amount'),
                'number' => $this->input('number'),
                'company_id' => $this->getCompany()->id,
                'status' => QuotationStatus::Sent,
                'signature' => $this->file('signature'),
                'note' => $this->input('note'),
            ],
            'quotation_items' => $this->input('work_services'),
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
            'vat_percentage' => floatval($this->input('vat_percentage') ?: 0),
            'discount_amount' => floatval($this->input('discount_amount') ?: 0),
            'potential_amount' => floatval($this->input('potential_amount') ?: 0),
        ]);
    }
}
