<?php

namespace App\Http\Requests\Company\WorkContracts;

use App\Enums\Setting\WorkContract\WorkContractContentTextType;
use App\Models\Setting\WorkContractSetting;
use App\Models\WorkContract\WorkContract;
use App\Rules\Helpers\Media;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SetAsDefaultFormatRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Solution instance container property.
     *
     * @var WorkContract|null
     */
    private ?WorkContract $workContract = null;
    /**
     * Solution instance container property.
     *
     * @var WorkContractSetting|null
     */
    private ?WorkContractSetting $workContractSetting = null;

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
     * Get Car based on the supplied input
     *
     * @return WorkContractSetting|null
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getWorkContractSetting(): ?WorkContractSetting
    {
        if ($this->workContractSetting) {
            return $this->workContractSetting;
        }
        $company = $this->getCompany();

        return $this->workContractSetting = WorkContractSetting::whereCompanyId($company->id)->firstOrFail();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->fresh()->can('set-default-work-contract');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        if ($this->has('work_contract_id')) {
            return [
                'work_contract_id' => [
                    'required',
                    'string',
                    Rule::exists('work_contracts', 'id')->where(function ($query) {
                        $query->where('company_id', $this->getCompany()->id);
                    }),
                ],
            ];
        }

        return [
            'footer' => ['required', 'string',],
            'signature' => ['required', 'image', 'max:' . Media::MAX_IMAGE_SIZE, 'mimes:' . Media::imageExtensions()],
            'foreword_contents' => ['required', 'array'],
            'foreword_contents.*.text' => ['required', 'string'],
            'foreword_contents.*.order_index' => ['required', 'integer'],
            'foreword_contents.*.text_type' => ['required', Rule::in(WorkContractContentTextType::getValues())],
            'contract_contents' => ['required', 'array'],
            'contract_contents.*.text' => ['required', 'string'],
            'contract_contents.*.order_index' => ['required', 'integer'],
            'contract_contents.*.text_type' => ['required', Rule::in(WorkContractContentTextType::getValues())],
        ];
    }
}
