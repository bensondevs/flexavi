<?php

namespace App\Http\Requests\Company\WorkContractSettings;

use App\Enums\Setting\WorkContract\WorkContractContentTextType;
use App\Models\Setting\WorkContractSetting;
use App\Rules\Helpers\Media;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class UpdateWorkContractSettingRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Solution instance container property.
     *
     * @var WorkContractSetting|null
     */
    private ?WorkContractSetting $solution = null;

    /**
     * Get Car based on the supplied input
     *
     * @param bool $force
     * @return WorkContractSetting|null
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getWorkContractSetting(bool $force = false): ?WorkContractSetting
    {
        if ($this->solution instanceof WorkContractSetting and not($force)) {
            return $this->solution;
        }
        $company = $this->getCompany();

        return $this->solution = WorkContractSetting::whereCompanyId($company->id)->firstOrFail();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'footer' => ['required', 'string',],
            'foreword_contents' => ['required', 'array'],
            'foreword_contents.*.text' => ['required', 'string'],
            'foreword_contents.*.order_index' => ['required', 'integer'],
            'foreword_contents.*.text_type' => ['required', Rule::in(WorkContractContentTextType::getValues())],
            'contract_contents' => ['required', 'array'],
            'contract_contents.*.text' => ['required', 'string'],
            'contract_contents.*.order_index' => ['required', 'integer'],
            'contract_contents.*.text_type' => ['required', Rule::in(WorkContractContentTextType::getValues())],
            'signature' => [
                'nullable',
                'image',
                'max:' . Media::MAX_IMAGE_SIZE,
                'mimes:' . Media::imageExtensions(),
            ],
            'signature_name' => ['required', 'string'],
        ];

    }

    /**
     * Get work contract setting data
     *
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function workContractSettingData(): array
    {
        $company = $this->getCompany();
        return [
            'work_contract_data' => [
                'signature' => $this->file('signature'),
                'signature_name' => $this->input('signature_name'),
                'footer' => $this->input('footer'),
                'company_id' => $company->id,
            ],
            'work_contract_forewords_data' => $this->input('foreword_contents', []),
            'work_contract_contracts_data' => $this->input('contract_contents', []),
        ];
    }
}
