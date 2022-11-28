<?php

namespace App\Http\Requests\Company\WorkContracts;

use App\Models\Setting\WorkContractSetting;
use App\Models\WorkContract\WorkContract;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class UseCompanyFormatRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Solution instance container property.
     *
     * @var WorkContract|null
     */
    private ?WorkContract $workContract = null;

    /**
     * Work contract setting instance container property.
     *
     * @var WorkContractSetting|null
     */
    private ?WorkContractSetting $workContractSetting = null;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->fresh()->can('apply-company-format-work-contract', $this->getWorkContract());
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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'work_contract_id' => ['required', 'string', Rule::exists('work_contracts', 'id')],
        ];
    }
}
