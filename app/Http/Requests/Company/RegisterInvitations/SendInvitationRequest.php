<?php

namespace App\Http\Requests\Company\RegisterInvitations;

use App\Models\Invitation\RegisterInvitation;
use App\Traits\InputRequest;
use Illuminate\Foundation\Http\FormRequest;

class SendInvitationRequest extends FormRequest
{
    use InputRequest;

    /**
     * RegisterInvitation object
     *
     * @var RegisterInvitation|null
     */
    private $invitation;

    /**
     * Get RegisterInvitation based on supplied input
     *
     * @return RegisterInvitation
     */
    public function getInvitation()
    {
        $user = $this->user();
        $company = $user->owner->company;

        return $this->invitation = $this->model =
            $this->invitation ?:
                new RegisterInvitation(['company_id' => $company->id]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user()->fresh();

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
        $this->addRule('attached_role', [
            'required',
            'string',
            'exists:roles,name',
        ]);

        return $this->returnRules();
    }
}
