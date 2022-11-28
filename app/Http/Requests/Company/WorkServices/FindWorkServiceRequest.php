<?php

namespace App\Http\Requests\Company\WorkServices;

use App\Models\WorkService\WorkService;
use App\Traits\CompanyPopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FindWorkServiceRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    /**
     * Found target work service container
     *
     * @var WorkService|null
     */
    private ?WorkService $workService = null;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $workService = $this->getWorkService();

        return $this->user()
            ->fresh()
            ->can('view-work-service', $workService);
    }

    /**
     * Get WorkService based on supplied input
     *
     * @return WorkService|null
     */
    public function getWorkService(): ?WorkService
    {
        if ($this->workService) {
            return $this->workService;
        }
        $id = $this->input('work_service_id');

        return $this->workService = WorkService::findOrFail($id);
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
