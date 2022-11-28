<?php

namespace App\Http\Requests\Company\OwnerInvitations;

use App\Enums\OwnerInvitation\OwnerInvitationStatus;
use App\Models\Owner\OwnerInvitation;
use Illuminate\Foundation\Http\FormRequest;

class OwnerInvitationActionRequest extends FormRequest
{
    /**
     * OwnerInvitation object
     *
     * @var  OwnerInvitation|null
     */
    private $invitation;

    /**
     * Get OwnerInvitation based on supplied input
     *
     * @return  OwnerInvitation
     */
    public function getInvitation()
    {
        $code = $this->input('code') ?: $this->input('code');
        $this->invitation = OwnerInvitation::findByCode($code, true);

        if ($this->invitation->checkExpired()) {
            abort(422, 'Invitation expired.');
        }

        if ($this->invitation->status == OwnerInvitationStatus::Used) {
            abort(422, 'Invitation has been used.');
        }

        return $this->invitation;
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
        return [
            //
        ];
    }
}
