<?php

namespace App\Http\Requests\Company\EmployeeInvitations;

use App\Enums\RegisterInvitation\RegisterInvitationStatus;
use App\Models\Employee\EmployeeInvitation;
use Illuminate\Foundation\Http\FormRequest;

class CancelEmployeeInvitationRequest extends FormRequest
{
    /**
     * SendEmployeeInvitation object
     *
     * @var  EmployeeInvitation|null
     */
    private $invitation;

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
            ->can('cancel-invitation-employee', $invitation);
    }

    /**
     * Get SendEmployeeInvitation based on supplied input
     *
     * @return  EmployeeInvitation
     */

    public function getInvitation()
    {
        if ($this->invitation) {
            return $this->invitation;
        }
        $id = $this->input('id');

        return $this->invitation = EmployeeInvitation::findOrFail($id);
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
