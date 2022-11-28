<?php

namespace App\Http\Requests\Company\WorkContracts;

use App\Enums\Setting\WorkContract\WorkContractContentTextType;
use App\Enums\WorkContract\WorkContractStatus;
use App\Models\WorkContract\WorkContract;
use App\Rules\FloatValue;
use App\Rules\Helpers\Media;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class DraftWorkContractRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * work contract instance container property.
     *
     * @var WorkContract|null
     */
    private ?WorkContract $workContract = null;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $workContract = null;
        if ($this->has('work_contract_id')) {
            $workContract = $this->getWorkContract();
        }
        return $this->user()->fresh()->can('draft-work-contract', $workContract);
    }

    /**
     * Get work contract based on the supplied input
     *
     * @return WorkContract|null
     */
    public function getWorkContract(): ?WorkContract
    {
        if ($this->workContract) {
            return $this->workContract;
        }

        $id = $this->input('work_contract_id');
        return $this->workContract = WorkContract::whereStatus(WorkContractStatus::Drafted)->findOrFail($id);
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
        $numberRules = ['required', 'alpha_dash', Rule::unique('work_contracts', 'number')->where(function ($query) {
            return $query->where('company_id', $this->getCompany()->id);
        })];

        if ($this->has('work_contract_id')) {
            $numberRules = ['required', 'alpha_dash', Rule::unique('work_contracts', 'number')->where(function ($query) {
                return $query->where('company_id', $this->getCompany()->id);
            })->ignore($this->getWorkContract()->id)];
        }
        return [
            'work_contract_id' => ['nullable', 'string', Rule::exists('work_contracts', 'id')],
            'customer_id' => ['required', 'string', Rule::exists('customers', 'id')],
            'footer' => ['nullable', 'string'],

            // Foreword content
            'foreword_contents' => ['nullable', 'array'],
            'foreword_contents.*.text' => ['nullable', 'string'],
            'foreword_contents.*.order_index' => ['nullable', 'integer'],
            'foreword_contents.*.text_type' => ['nullable', Rule::in(WorkContractContentTextType::getValues())],

            // Contract content
            'contract_contents' => ['nullable', 'array'],
            'contract_contents.*.text' => ['nullable', 'string'],
            'contract_contents.*.order_index' => ['nullable', 'integer'],
            'contract_contents.*.text_type' => ['nullable', Rule::in(WorkContractContentTextType::getValues())],

            'items' => ['nullable', 'array'],
            'items.*.work_service_id' => ['nullable', Rule::exists('work_services', 'id')],
            'items.*.amount' => ['nullable', 'numeric'],
            'potential_amount' => ['nullable', new FloatValue(true)],
            'discount_amount' => ['nullable', new FloatValue(true)],
            'number' => $numberRules,
            'signature_use_type' => ['nullable', 'string', Rule::in(['upload', 'default'])],
            'signature' => ['required_if:signature_use_type,upload', 'image', 'max:' . Media::MAX_IMAGE_SIZE, 'mimes:' . Media::imageExtensions()],
            'signature_name' => ['required_if:signature_use_type,upload', 'string'],
        ];
    }


    /**
     * Get quotation data with injected value
     *
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function workContractData(): array
    {
        return [
            'work_contract_data' => [
                'customer_id' => $this->input('customer_id'),
                'potential_amount' => $this->input('potential_amount'),
                'discount_amount' => $this->input('discount_amount'),
                'number' => $this->input('number'),
                'footer' => $this->input('footer'),
                'company_id' => $this->getCompany()->id,
                'status' => WorkContractStatus::Drafted,
                'signature_use_type' => $this->input('signature_use_type'),
                'signature' => $this->file('signature'),
                'signature_name' => $this->input('signature_name'),
            ],
            'work_contract_forewords_data' => $this->input('foreword_contents', []),
            'work_contract_contracts_data' => $this->input('contract_contents', []),
            'items_data' => $this->input('items', []),
        ];
    }

    /**
     * prepare the inputs request for validation
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'discount_amount' => $this->input('discount_amount', 0),
            'potential_amount' => $this->input('potential_amount', 0),
        ]);
    }
}
