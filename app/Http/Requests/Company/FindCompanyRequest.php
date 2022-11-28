<?php

namespace App\Http\Requests\Company;

use App\Rules\Helpers\Media;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;

class FindCompanyRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $user = $this->user()->fresh();

        switch (true) {
            case urlContains("/check"):
                return true;
            case urlContains("/view") || urlContains("/self"):
                return $user->can('view-company', $this->getCompany());
            case urlContains("/delete") && $this->get("force"):
                return $user->can('force-delete-company', $this->getCompany());
            case urlContains("/delete"):
                return $user->can('delete-company', $this->getCompany());
            case urlContains("/restore"):
                return $user->can('restore-company', $this->getCompany(true));
            default:
                return false;
        }
    }

    /**
     * Prepare input request before validation
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $force = $this->input('force');
        $this->merge(['force' => strtobool($force)]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }
}
