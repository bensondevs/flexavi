<?php

namespace App\Http\Requests\Company\PendingInvitations;

use App\Enums\RegisterInvitation\RegisterInvitationStatus;
use App\Models\Invitation\RegisterInvitation;
use Illuminate\Foundation\Http\FormRequest;

class CancelOwnerInvitationRequest extends FormRequest
{
    /**
     * RegisterInvitation model container
     *
     * @var RegisterInvitation|null
     */
    protected $invitation;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $invitation = $this->getInvitation();
        if ($invitation->status != RegisterInvitationStatus::Active) {
            abort(422, 'This invitation has expired or user.');
        }

        return $this->user()
            ->fresh()
            ->can('cancel-owner-invitation');
    }

    /**
     * Get RegisterInvitation based on supplied input
     *
     * @return RegisterInvitation
     */
    public function getInvitation()
    {
        if ($this->invitation) {
            return $this->invitation;
        }
        $id = $this->input('id');

        return $this->invitation = RegisterInvitation::findOrFail($id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
