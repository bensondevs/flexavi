<?php

namespace App\Http\Requests\Company\WorkContracts;

use App\Enums\Setting\WorkContract\WorkContractContentTextType;
use App\Enums\WorkContract\WorkContractStatus;
use App\Models\WorkContract\WorkContract;
use App\Rules\FloatValue;
use App\Rules\Helpers\Media;
use App\Rules\UniqueWithConditions;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SaveWorkContractRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Solution instance container property.
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
        if ($this->has('work_contract_id')) {
            $workContract = $this->getWorkContract();
            return $this->user()->fresh()->can('edit-work-contract', $workContract);
        }
        return $this->user()->fresh()->can('create-work-contract');
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
        return $this->workContract = WorkContract::findOrFail($id);
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
        return [
            'work_contract_id' => [
                'nullable',
                'string',
                Rule::exists('work_contracts', 'id')->where(function ($query) {
                    $query->where('company_id', $this->getCompany()->id);
                }),
            ],
            'customer_id' => ['required', 'string', Rule::exists('customers', 'id')],
            'footer' => ['nullable', 'string'],

            // Foreword content
            'foreword_contents' => ['required', 'array'],
            'foreword_contents.*.text' => ['required', 'string'],
            'foreword_contents.*.order_index' => ['required', 'integer'],
            'foreword_contents.*.text_type' => ['required', Rule::in(WorkContractContentTextType::getValues())],

            // Contract content
            'contract_contents' => ['required', 'array'],
            'contract_contents.*.text' => ['required', 'string'],
            'contract_contents.*.order_index' => ['required', 'integer'],
            'contract_contents.*.text_type' => ['required', Rule::in(WorkContractContentTextType::getValues())],

            'work_services' => ['required', 'array'],
            'work_services.*.work_service_id' => ['nullable', Rule::exists('work_services', 'id')],
            'work_services.*.amount' => ['nullable', 'numeric'],
            'logo' => ['nullable', 'image', 'max:' . Media::MAX_IMAGE_SIZE, 'mimes:' . Media::imageExtensions()],
            'potential_amount' => ['nullable', new FloatValue(true)],
            'vat_percentage' => ['nullable', new FloatValue(true)],
            'discount_amount' => ['nullable', new FloatValue(true)],
            'status' => ['required', Rule::in([WorkContractStatus::Sent])],
            'number' => [
                'required',
                'alpha_dash',
                new UniqueWithConditions(
                    new WorkContract(),
                    [
                        'company_id' => $this->getCompany()->id
                    ]
                )
            ],
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
                'vat_percentage' => $this->input('vat_percentage'),
                'discount_amount' => $this->input('discount_amount'),
                'number' => $this->input('number'),
                'footer' => $this->input('footer'),
                'company_id' => $this->getCompany()->id,
                'status' => $this->input('status'),
            ],
            'work_contract_forewords_data' => $this->input('foreword_contents', []),
            'work_contract_contracts_data' => $this->input('contract_contents', []),
            'work_contract_work_services_data' => $this->input('work_services', []),
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
            'vat_percentage' => $this->input('vat_percentage', 0),
            'discount_amount' => $this->input('discount_amount', 0),
            'potential_amount' => $this->input('potential_amount', 0),
        ]);
    }
}
