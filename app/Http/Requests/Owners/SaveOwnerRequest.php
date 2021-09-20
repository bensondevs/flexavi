<?php

namespace App\Http\Requests\Owners;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\CompanyInputRequest;

use App\Models\Owner;

class SaveOwnerRequest extends FormRequest
{
    use CompanyInputRequest;

    private $owner;

    public function getOwner()
    {
        if ($this->owner) return $this->owner;

        $id = $this->input('id') ?: $this->input('owner_id');
        return $this->owner = Owner::findOrFail($id);
    }

    protected function prepareForValidation()
    {
        if (! $this->has('company_id')) {
            $company = $this->getCompany();
            $this->merge(['company_id' => $company->id]);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create-owner');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
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
}
