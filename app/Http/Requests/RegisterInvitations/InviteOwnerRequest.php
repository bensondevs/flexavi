<?php

namespace App\Http\Requests\RegisterInvitations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\CompanyInputRequest;

use App\Models\Owner;

class InviteOwnerRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('send-owner-register-invitation');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'invited_email' => ['required', 'string', 'email', 'unique:users,email'],
            'expiry_time' => ['date'],
        ]);

        return $this->returnRules();
    }

    public function invitationData()
    {
        $input = $this->validated();
        $input['role'] = 'owner';
        $input['attachments'] = [
            'is_prime_owner' => false,
            'company_id' => $this->getCompany()->id,
        ];

        return $input;
    }
}
