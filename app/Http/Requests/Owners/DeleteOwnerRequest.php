<?php

namespace App\Http\Requests\Owners;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Owner;

class DeleteOwnerRequest extends FormRequest
{
    private $targetedOwner;

    public function getTargetedOwner()
    {
        return $this->targetedOwner = ($this->targetedOwner) ?:
            Owner::findOrFail($this->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        $targetedOwner = $this->getTargetedOwner();

        // Action is authorized by permision owned
        $actionAuthorized = $user->hasCompanyPermission(
            $targetedOwner->company_id, 
            'delete owner'
        );

        // Target is not prime owner
        $targetAllowed = (! $targetedOwner->is_prime_owner);
        return ($actionAuthorized && $targetAllowed);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
