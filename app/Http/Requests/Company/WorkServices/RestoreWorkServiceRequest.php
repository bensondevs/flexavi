<?php

namespace App\Http\Requests\Company\WorkServices;

use App\Models\WorkService\WorkService;
use App\Traits\CompanyPopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RestoreWorkServiceRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    /**
     * Found target work service container
     *
     * @var WorkService|null
     */
    private ?WorkService $trashedWorkService = null;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $trashedWorkService = $this->getTrashedWorkService();

        return $this->user()
            ->fresh()
            ->can('restore-work-service', $trashedWorkService);
    }

    /**
     * Get Trashed WorkService based on supplied input
     *
     * @return WorkService|null
     */
    public function getTrashedWorkService(): ?WorkService
    {
        if ($this->trashedWorkService) {
            return $this->trashedWorkService;
        }
        $id = $this->input('work_service_id');

        return $this->trashedWorkService = WorkService::onlyTrashed()->findOrFail(
            $id
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $company = $this->getCompany();
        return [
            'work_service_id' => ['required', 'string', Rule::exists('work_services', 'id')->where('company_id', $company->id)],
        ];
    }
}
