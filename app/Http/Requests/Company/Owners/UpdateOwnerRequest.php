<?php

namespace App\Http\Requests\Company\Owners;

use App\Models\Owner\Owner;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOwnerRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Owner object
     *
     * @var Owner|null
     */
    private $owner;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()
            ->fresh()
            ->can('edit-owner', $this->getOwner());
    }

    /**
     * Get Owner based on supplied input
     *
     * @return Owner
     */
    public function getOwner()
    {
        if ($this->owner) {
            return $this->owner;
        }
        $id = $this->input('id') ?: $this->input('owner_id');

        return $this->owner = Owner::with('user')->findOrFail($id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $owner = $this->getOwner();

        $this->setRules([
            'id' => ['required', 'string'],
            'fullname' => ['required', 'string'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($owner->user_id)
            ],
            'phone' => [
                'required',
                'numeric',
                Rule::unique('users', 'phone')->ignore($owner->user_id)
            ],
        ]);

        return $this->returnRules();
    }

    /**
     * Prepare for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (!$this->has('company_id')) {
            $company = $this->getCompany();
            $this->merge(['company_id' => $company->id]);
        }
    }
}
