<?php

namespace App\Http\Requests\Auths;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\InputRequest;

use App\Rules\HasUpperCase;
use App\Rules\HasLowerCase;
use App\Rules\HasNumerical;
use App\Rules\HasSpecialCharacter;
use App\Rules\Base64Image;

use App\Models\Owner;
use App\Models\RegisterInvitation;

use App\Enums\RegisterInvitation\RegisterInvitationStatus;

class RegisterRequest extends FormRequest
{
    use InputRequest;

    private $invitation;

    public function getInvitation()
    {
        if ($this->invitation) {
            return $this->invitation;
        }

        $invitationCode = $this->input('invitation_code');
        if (! $invitationCode) {
            return null;
        }

        $invitation = RegisterInvitation::findByCode($invitationCode);
        if ($invitation->status != RegisterInvitationStatus::Active) {
            return abort(422, 'Cannot use this register invitation');
        }

        return $this->invitation = $invitation;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
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
            'birth_date' => ['required', 'date'],
            'id_card_type' => ['required', 'string'],
            'id_card_number' => ['required', 'string'],
            'phone' => ['required', 'string'],

            'address' => ['required', 'string'],
            'house_number' => ['required', 'integer'],
            'house_number_suffix' => ['string'],
            'zipcode' => ['required', 'integer'],
            'city' => ['required', 'string'],
            'province' => ['required', 'string'],

            'profile_picture' => ['required', 'image', 'mimes:jpg,jpeg,png,svg,bmp'],
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

        if (is_base64_string($this->input('profile_picture'))) {
            $this->rules['profile_picture'] = ['required', new Base64Image()];
        }

        return $this->returnRules();
    }

    public function userData()
    {
        $userData = $this->except([
            'profile_picture',
            'confirm_password',
        ]);

        if ($invitation = $this->getInvitation()) {
            $userData['registration_code'] = $invitation->registration_code;
        }

        return $userData;
    }

    public function getAddressData()
    {
        return $this->only([
            'address',
            'house_number',
            'house_number_suffix',
            'zipcode',
            'city',
            'province',
        ]);
    }

    public function getAttachments()
    {
        $invitation = $this->getInvitation();
        if (! $invitation) {
            return [];
        }

        if ($invitation->status != RegisterInvitationStatus::Active) {
            abort(422, 'The invitation has been used');
        }

        return $invitation->attachments;
    }
}
