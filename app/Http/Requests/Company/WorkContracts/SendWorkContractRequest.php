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

class SendWorkContractRequest extends FormRequest
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
        if (!$this->has('work_contract_id')) {
            return $this->user()->fresh()->can('create-work-contract');
        }

        $workContract = $this->getWorkContract();

        if ($workContract->isDrafted()) {
            return $this->user()->fresh()->can('send-work-contract', $workContract);
        }

        if (in_array($workContract->status, [WorkContractStatus::Sent, WorkContractStatus::Signed])) {
            return $this->user()->fresh()->can('resend-work-contract', $workContract);
        }

        return false;
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
     * @return array<string, mixed>
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function rules(): array
    {
        $company = $this->getCompany();

        $numberRules = ['required', 'alpha_dash', Rule::unique('work_contracts', 'number')->where('company_id', $company->id)];
        if ($this->has('work_contract_id')) {
            $numberRules = ['required', 'alpha_dash', Rule::unique('work_contracts', 'number')->where('company_id', $company->id)->ignore($this->getWorkContract()->id)];
        }
        return [
            'work_contract_id' => [
                'nullable',
                'string',
                Rule::exists('work_contracts', 'id')->where('company_id', $company->id)
            ],
            'number' => $numberRules,
            'customer_id' => ['required', 'string', Rule::exists('customers', 'id')->where('company_id', $company->id)],
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

            'items' => ['required', 'array'],
            'items.*.work_service_id' => ['required', Rule::exists('work_services', 'id')],
            'items.*.amount' => ['required', 'numeric'],
            'potential_amount' => ['required', new FloatValue(true)],
            'discount_amount' => ['required', new FloatValue(true)],
            'signature_use_type' => ['required', 'string', Rule::in(['upload', 'default'])],
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
                'status' => WorkContractStatus::Sent,
                'sent_at' => now(),
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
