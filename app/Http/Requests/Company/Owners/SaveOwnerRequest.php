<?php

namespace App\Http\Requests\Company\Owners;

use App\Models\Owner\Owner;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;

class SaveOwnerRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Owner object
     *
     * @var Owner|null
     */
    private ?Owner $owner = null;

    /**
     * Get Owner based on supplied input
     *
     * @return Owner|null
     */
    public function getOwner(): ?Owner
    {
        if ($this->owner) {
            return $this->owner;
        }
        $id = $this->input('id') ?: $this->input('owner_id');

        return $this->owner = Owner::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()
            ->fresh()
            ->can('create-owner');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $this->setRules([
            'company_id' => ['required', 'string'],
            'bank_name' => ['required', 'string'],
            'bic_code' => ['required', 'string'],
            'bank_account' => ['required', 'string'],
            'bank_holder_name' => ['required', 'string'],
        ]);

        return $this->returnRules();
    }

    /**
     * Prepare for validation
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        if (!$this->has('company_id')) {
            $company = $this->getCompany();
            $this->merge(['company_id' => $company->id]);
        }
    }
}
