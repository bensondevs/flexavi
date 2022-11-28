<?php

namespace App\Http\Requests\Company\Quotations;

use App\Enums\Quotation\QuotationStatus;
use App\Models\Customer\Customer;
use App\Models\Quotation\Quotation;
use App\Rules\FloatValue;
use App\Rules\Helpers\Media;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class UpdateQuotationRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Found Quotation model container
     *
     * @var Quotation|null
     */
    private ?Quotation $quotation = null;

    /**
     * Found Customer model container
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
        $quotation = $this->getQuotation();
        $customer = $this->getCustomer();
        return $this->user()
            ->fresh()
            ->can('edit-quotation', [$quotation, $customer]);
    }

    /**
     * Get Quotation based on supplied input
     *
     * @return Quotation|null
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
        $id = $this->input('customer_id');

        return $this->customer = Customer::findOrFail($id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function rules(): array
    {
        $company = $this->getCompany();

        return [
            'quotation_id' => ['required', 'string', Rule::exists('quotations', 'id')],
            'customer_address' => ['required', 'string'],
            'date' => ['required', 'date'],
            'expiry_date' => ['required', 'date', 'after_or_equal:date'],
            'note' => ['nullable', 'string'],
            'vat_percentage' => ['nullable', new FloatValue(true)],
            'discount_amount' => ['nullable', new FloatValue(true)],
            'potential_amount' => ['nullable', new FloatValue(true)],
            'signature' => [
                'nullable',
                'image',
                'max:' . Media::MAX_IMAGE_SIZE,
                'mimes:' . Media::imageExtensions(),
            ],
            'status' => [
                'required',
                Rule::in([
                    QuotationStatus::Created,
                    QuotationStatus::Sent,
                    QuotationStatus::Drafted
                ])
            ],
            'work_services' => ['required', 'array'],
            'work_services.*.work_service_id' => [
                'nullable',
                Rule::exists('work_services', 'id')
                    ->where('company_id', $company->id),
            ],
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
        $data = [
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
                'signature' => $this->file('signature'),
                'note' => $this->input('note'),
                'status' => $this->input('status'),
            ],
            'quotation_items' => $this->input('work_services'),
        ];

        if ($this->input('status') == QuotationStatus::Sent) {
            $data['quotation_data']['sent_at'] = now();
        }

        if (in_array($this->input('status'), [QuotationStatus::Drafted, QuotationStatus::Created])) {
            $data['quotation_data']['sent_at'] = null;
        }

        return $data;
    }

    /**
     * Format input payload before validation
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'vat_percentage' => floatval($this->input('vat_percentage') ?: 0),
            'discount_amount' => floatval($this->input('discount_amount') ?: 0),
            'potential_amount' => floatval($this->input('potential_amount') ?: 0),
        ]);
    }
}
