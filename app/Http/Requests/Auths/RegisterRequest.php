<?php

namespace App\Http\Requests\Auths;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\InputRules;

use App\Rules\HasUpperCase;
use App\Rules\HasLowerCase;
use App\Rules\HasNumerical;
use App\Rules\HasSpecialCharacter;

use App\Models\RegisterInvitation;

class RegisterRequest extends FormRequest
{
    use InputRules;

    private $invitation;

    public function getInvitation()
    {
        $invitationCode = $this->input('invitation_code');

        if (! $invitationCode) return null;

        return $this->invitation = $this->invitation ?:
            RegisterInvitation::findByCode($invitationCode);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /* Invited email */
        $insertedEmail = request()->input('email');
        $invitation = $this->getInvitation();

        if (! $invitation) return true;

        return ($invitation->invited_email == $insertedEmail);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'fullname' => ['required', 'string'],
            'salutation' => ['required', 'string'],
            'birth_date' => ['required', 'date'],
            'id_card_type' => ['required', 'string'],
            'id_card_number' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'address' => ['required', 'string'],
            'profile_picture' => ['required', 'file', 'mimes:jpg,jpeg,svg,png'],
            'email' => ['required', 'string', 'email', 'unique:users,email'],
            'password' => [
                'required', 
                'string', 
                'min:8', 
                new HasUpperCase, 
                new HasLowerCase, 
                new HasNumerical, 
                new HasSpecialCharacter,
            ],
            'confirm_password' => ['required', 'string', 'same:password'],
        ]);

        return $this->returnRules();
    }
}
