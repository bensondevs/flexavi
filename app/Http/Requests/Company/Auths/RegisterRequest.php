<?php

namespace App\Http\Requests\Company\Auths;

use App\Enums\RegisterInvitation\RegisterInvitationStatus;
use App\Enums\User\UserSocialiteAccountType;
use App\Models\Invitation\RegisterInvitation;
use App\Rules\{HasLowerCase, HasNumerical, HasSpecialCharacter, HasUpperCase};
use App\Traits\InputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    use InputRequest;

    /**
     * RegisterInvitation object
     *
     * @var mixed|null
     */
    private mixed $invitation = null;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            // Personal information
            'invitation_code' => ['nullable', 'string'],
            'fullname' => ['required', 'string'],
            'password' => [
                'required',
                'string',
                'min:8',
                new HasUpperCase(),
                new HasLowerCase(),
                new HasNumerical(),
                new HasSpecialCharacter(),
            ],
            'confirm_password' => ['required', 'string', 'same:password'],
            'email' => ['required', 'string', 'email', Rule::unique('users', 'email')],
            'birth_date' => ['nullable', 'date'],

            // Address
            'address' => ['required', 'string'],
            'house_number' => ['required', 'string'],
            'house_number_suffix' => ['string'],
            'zipcode' => ['required', 'string'],
            'city' => ['required', 'string'],
            'province' => ['required', 'string'],
            'provider' => ['nullable', Rule::in(UserSocialiteAccountType::getValues())],
            'provider_id' => ['required_with:provider'],
        ];
    }

    /**
     * Get user data
     *
     * @return array
     */
    public function userData(): array
    {
        $userData = $this->except(['profile_picture', 'confirm_password']);
        if ($invitation = $this->getInvitation()) {
            $userData['registration_code'] = $invitation->registration_code;
        }

        if ($this->input('provider')) {
            $userData['email_verified_at'] = now();
        }

        return $userData;
    }

    /**
     * Get RegisterInvitation based on supplied input
     *
     * @return mixed|null
     */
    public function getInvitation(): mixed
    {
        if ($this->invitation) {
            return $this->invitation;
        }

        $invitationCode = $this->input('invitation_code');
        if (!$invitationCode) {
            return null;
        }

        $invitation = RegisterInvitation::whereRegistrationCode($invitationCode)->first();

        if (!$invitation) {
            abort(422, 'Registration code not found.');
        }

        if ($invitation->status != RegisterInvitationStatus::Active or now()->isAfter($invitation->expiry_time)) {
            abort(422, 'Cannot use registration code because the code already use or expired.');
        }

        $this->invitation = $invitation;
        return $this->invitation;
    }

    /**
     * Get user socialite data
     *
     * @return array|null
     */
    public function userSocialiteData(): array|null
    {
        if ($this->input('provider')) {
            return [
                'type' => $this->input('provider'),
                'vendor_user_id' => $this->input('provider_id')
            ];
        }
        return null;
    }

    /**
     * Get user address data
     *
     * @return array
     */
    public function getAddressData(): array
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

    /**
     * Get user attachment data
     *
     * @return array
     */
    public function getAttachments(): array
    {
        $invitation = $this->getInvitation();
        if (!$invitation) {
            return [];
        }
        if ($invitation->status != RegisterInvitationStatus::Active) {
            abort(422, 'The invitation has been used');
        }

        return $invitation->attachments;
    }
}
