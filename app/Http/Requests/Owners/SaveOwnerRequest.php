<?php

namespace App\Http\Requests\Owners;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\CompanyInputRequest;

use App\Models\Owner;

class SaveOwnerRequest extends FormRequest
{
    use CompanyInputRequest;

    private $owner;

    public function getOwner()
    {
        return $this->owner = $this->model = ($this->owner) ?:
            Owner::findOrFail($this->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Allow action
        $actionAuthorized = $this->authorizeCompanyAction('owners');
        if ($this->isMethod('POST')) return $actionAuthorized;

        // Gather important data
        $user = $this->user();
        $owner = $this->getOwner();

        // Allow unused owner account
        if (! $owner->user_id) return true;

        // Allow self editing
        if ($owner->user->id == $user->id) return true;

        $isPrimeOwner = $owner->is_prime_owner;
        return ($actionAuthorized && $isPrimeOwner);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'bank_name' => ['required', 'string'],
            'bic_code' => ['required', 'string'],
            'bank_account' => ['required', 'string'],
            'bank_holder_name' => ['required', 'string'],
        ]);

        return $this->returnRules();
    }
}
