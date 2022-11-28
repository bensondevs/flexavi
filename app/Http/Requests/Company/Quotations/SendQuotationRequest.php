<?php

namespace App\Http\Requests\Company\Quotations;

use App\Enums\Quotation\QuotationStatus;
use App\Models\Quotation\Quotation;
use App\Rules\FloatValue;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SendQuotationRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Quotation object
     *
     * @var Quotation|null
     */
    private ?Quotation $quotation = null;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if (!$this->has('quotation_id') or (!$this->getQuotation())) {
            return $this->user()->fresh()->can('create-quotation');
        }

        $quotation = $this->getQuotation();
        if ($quotation->isDrafted()) {
            return $this->user()->fresh()->can('send-quotation', $quotation);

        }

        if (in_array($quotation->status, [QuotationStatus::Sent, QuotationStatus::Signed])) {
            return $this->user()->fresh()->can('resend-quotation', $quotation);
        }

        return false;
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
        return $this->quotation = Quotation::find($id);
    }

    /**
     * Prepare for validation
     *
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function rules(): array
    {
        $company = $this->getCompany();

        $rules = [
            // Work service related information
            'work_services' => ['required', 'array'],
            'work_services.*.work_service_id' => [
                'nullable',
                Rule::exists('work_services', 'id')
                    ->where('company_id', $company->id),
            ],
            'work_services.*.amount' => ['nullable', 'numeric'],
        ];

        if ($this->isQuotationValueChanged('customer_id')) {
            $rules['customer_id'] = [
                'required',
                'string',
                Rule::exists('customers', 'id')
                    ->where('company_id', $company->id),
            ];
        }

        if ($this->isQuotationValueChanged('customer_address')) {
            $rules['customer_address'] = [
                'required',
                'string'
            ];
        }

        if ($this->isQuotationValueChanged('number')) {
            $rules['number'] = [
                'required',
                'string'
            ];
        }

        if ($this->isQuotationValueChanged('date')) {
            $rules['date'] = [
                'required',
                'string'
            ];
        }

        if ($this->isQuotationValueChanged('expiry_date')) {
            $rules['expiry_date'] = [
                'required',
                'date',
                'after_or_equal:date',
            ];
        }

        if ($this->isQuotationValueChanged('note')) {
            $rules['note'] = [
                'nullable',
                'string',
            ];
        }

        if ($this->isQuotationValueChanged('vat_percentage')) {
            $rules['vat_percentage'] = ['nullable', new FloatValue(true)];
        }

        if ($this->isQuotationValueChanged('discount_amount')) {
            $rules['discount_amount'] = ['nullable', new FloatValue(true)];
        }

        if ($this->isQuotationValueChanged('potential_amount')) {
            $rules['potential_amount'] = ['nullable', new FloatValue(true)];
        }

        return $rules;
    }

    /**
     * Check whether quotation value has been changed or not.
     *
     * @param string $attributeName
     * @param string|null $input
     * @return bool
     */
    private function isQuotationValueChanged(string $attributeName, ?string $input = null): bool
    {
        if (!$this->has('quotation_id')) {
            return true;
        }

        if (!$quotation = $this->getQuotation()) {
            return true;
        }

        $input = $input ?: $this->input($attributeName);
        return $quotation->{$attributeName} === $input;
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
                'status' => QuotationStatus::Sent,
                'sent_at' => now(),
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
