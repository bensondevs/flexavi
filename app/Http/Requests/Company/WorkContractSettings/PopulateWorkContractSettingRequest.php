<?php

namespace App\Http\Requests\Company\WorkContractSettings;

use App\Models\Setting\WorkContractSetting;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class PopulateWorkContractSettingRequest extends FormRequest
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

        return $this->solution = WorkContractSetting::with('forewordContents', 'contractContents')->whereCompanyId($company->id)->firstOrFail();
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
            //
        ];
    }
}
