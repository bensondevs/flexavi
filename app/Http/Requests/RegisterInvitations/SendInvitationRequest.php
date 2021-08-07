<?php

namespace App\Http\Requests\RegisterInvitations;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\InputRequest;

use App\Models\RegisterInvitation;

class SendInvitationRequest extends FormRequest
{
    use InputRequest;

    private $invitation;

    public function getInvitation()
    {
        $user = $this->user();
        $company = $user->owner->company;

        return $this->invitation = $this->model = ($this->invitation) ?:
            new RegisterInvitation(['company_id' => $company->id]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user(); 

        return $user->hasRole('owner');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'invited_email' => ['required', 'string', 'email'],
            'expiry_time' => ['datetime'],
        ]);

        $this->addRule([
            'attached_role' => ['required', 'string', 'exists:roles,name']
        ]);

        return $this->returnRules();
    }
}
