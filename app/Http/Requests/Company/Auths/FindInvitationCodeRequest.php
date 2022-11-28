<?php

namespace App\Http\Requests\Company\Auths;

use App\Models\Invitation\RegisterInvitation;
use Illuminate\Foundation\Http\FormRequest;

class FindInvitationCodeRequest extends FormRequest
{
    /**
     * Solution instance container property.
     *
     * @var RegisterInvitation|null
     */
    private ?RegisterInvitation $registerInvitation = null;

    /**
     * Get Car based on the supplied input
     *
     * @param bool $force
     * @return RegisterInvitation|null
     */
    public function getRegisterInvitation(bool $force = false): ?RegisterInvitation
    {
        if ($this->registerInvitation instanceof RegisterInvitation and not($force)) {
            return $this->registerInvitation;
        }

        $code = $this->input('code');
        $invitation = RegisterInvitation::whereRegistrationCode($code)->firstOrFail();

        if (!$invitation->isUsableNow()) {
            abort(422, 'Cannot use registration code');
        }

        return $this->registerInvitation = $invitation;
    }

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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
