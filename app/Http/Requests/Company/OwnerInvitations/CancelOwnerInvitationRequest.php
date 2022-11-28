<?php

namespace App\Http\Requests\Company\OwnerInvitations;

use App\Enums\OwnerInvitation\OwnerInvitationStatus;
use App\Models\Owner\OwnerInvitation;
use Illuminate\Foundation\Http\FormRequest;

class CancelOwnerInvitationRequest extends FormRequest
{
    /**
     * OwnerInvitation object
     *
     * @var  OwnerInvitation|null
     */
    private ?OwnerInvitation $invitation = null;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $invitation = $this->getInvitation();
        if ($invitation->status != OwnerInvitationStatus::Active) {
            abort(422, 'This invitation has expired or user.');
        }

        return $this->user()
            ->fresh()
            ->can('cancel-invitation-owner', $this->getInvitation());
    }

    /**
     * Get OwnerInvitation based on supplied input
     *
     * @return OwnerInvitation|null
     */

    public function getInvitation(): ?OwnerInvitation
    {
        if ($this->invitation) {
            return $this->invitation;
        }
        $id = $this->input('id');

        return $this->invitation = OwnerInvitation::findOrFail($id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }
}
