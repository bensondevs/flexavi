<?php

namespace App\Http\Requests\Company\EmployeeInvitations;

use App\Enums\EmployeeInvitation\EmployeeInvitationStatus;
use App\Models\Employee\EmployeeInvitation;
use Illuminate\Foundation\Http\FormRequest;

class HandleActionRequest extends FormRequest
{
    /**
     * SendEmployeeInvitation object
     *
     * @var  EmployeeInvitation|null
     */
    private $invitation;

    /**
     * Get SendEmployeeInvitation based on supplied input
     *
     * @return  EmployeeInvitation
     */
    public function getInvitation()
    {
        $code = $this->input('code');
        $this->invitation = EmployeeInvitation::findByCode($code, true);
        if ($this->invitation->checkExpired()) abort(422, 'Invitation expired.');

        if ($this->invitation->status == EmployeeInvitationStatus::Used) abort(422, 'Invitation has been used.');

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
        return [];
    }
}
