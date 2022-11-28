<?php

namespace App\Http\Requests\Company\WorkServices;

use App\Enums\WorkService\WorkServiceStatus;
use App\Models\WorkService\WorkService;
use App\Rules\MoneyValue;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SaveWorkServiceRequest extends FormRequest
{
    use CompanyInputRequest;

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
        $user = $this->user()->fresh();
        if ($this->method() == 'POST') {
            return $user->can('create-work-service');
        }

        return $user->can('edit-work-service', $this->getWorkService());
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
        return [
            'company_id' => ['required', 'string'],
            'name' => ['required'],
            'unit' => ['required'],
            'description' => ['nullable'],
            'price' => ['required', new MoneyValue()],
            'status' => ['nullable', Rule::in(WorkServiceStatus::getValues())],
            'tax_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
        ];
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function prepareForValidation()
    {
        $company = $this->getCompany();
        $this->merge([
            'company_id' => $company->id,
            'status' => (int)$this->input('status'),
        ]);
    }
}
