<?php

namespace App\Http\Requests\Auths;

use Illuminate\Foundation\Http\FormRequest;
use ProtoneMedia\LaravelMixins\Request\ConvertsBase64ToFiles;

use App\Traits\InputRequest;

use App\Rules\HasUpperCase;
use App\Rules\HasLowerCase;
use App\Rules\HasNumerical;
use App\Rules\HasSpecialCharacter;
use App\Rules\Base64Image;

use App\Models\Owner;
use App\Models\RegisterInvitation;

class RegisterRequest extends FormRequest
{
    use InputRequest;

    private $invitation;

    public function getInvitation()
    {
        $invitationCode = $this->input('invitation_code');
        if (! $invitationCode) {
            return null;
        }

        if ($this->invitation) {
            return $this->invitation;
        }
        return $this->invitation = RegisterInvitation::findByCode($invitationCode);
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
            'salutation' => ['required', 'string'],
            'birth_date' => ['required', 'date'],
            'id_card_type' => ['required', 'string'],
            'id_card_number' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'address' => ['required', 'string'],
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

        if (is_string($this->input('profile_picture'))) {
            $picture = convertBase64ToUploadedFile($this->input('profile_picture'));
            $this->merge(['profile_picture' => $picture]);
            $this->addRule('profile_picture', ['required']);
        }

        // Has no invitation, then it must be owner
        if (! $this->input('invitation_code')) {
            $this->addRule('bank_name', ['required', 'string']);
            $this->addRule('bic_code', ['required', 'string']);
            $this->addRule('bank_account', ['required', 'string']);
            $this->addRule('bank_holder_name', ['required', 'string']);
        }

        return $this->returnRules();
    }

    public function userData()
    {
        return $this->except([
            'profile_picture',

            'confirm_password',

            'bank_name',
            'bic_code',
            'bank_account',
            'bank_holder_name',
        ]);
    }

    public function getOwnerData()
    {
        return $this->only([
            'bank_name',
            'bic_code',
            'bank_account',
            'bank_holder_name',
        ]);
    }

    public function getAttachments()
    {
        $invitation = $this->getInvitation();

        if ($invitation->status != 'expired') {
            abort(422, 'The invitation has been used');
        }

        return $invitation ? $invitation->attachments : [];
    }
}
