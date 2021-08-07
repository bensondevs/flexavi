<?php

namespace App\Http\Requests\RegisterInvitations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\CompanyInputRequest;

use App\Models\Employee;

use App\Enums\Employee\EmployeeType;
use App\Enums\Employee\EmployeeStatus;

class InviteEmployeeRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('send-employee-register-invitation');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'invited_email' => ['required', 'string', 'email', 'unique:users,email'],
            'expiry_time' => ['date'],

            'title' => ['required', 'string'],
            'employee_type' => [
                'required', 
                'numeric', 
                'min:' . EmployeeType::Administrative, 
                'max:' . EmployeeType::Roofer
            ],
        ]);

        return $this->returnRules();
    }

    public function invitationData()
    {
        $input = $this->only(['invited_email', 'expiry_time']);
        $input['attachments'] = $this->attachments();
        return $input;
    }

    public function attachments()
    {
        $attachments = $this->except(['invited_email', 'expiry_time']);
        $attachments['company_id'] = $this->getCompany()->id;

        return $attachments;
    }
}