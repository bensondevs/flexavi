<?php

namespace App\Http\Requests\RegisterInvitations;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\CompanyInputRequest;

use App\Models\Employee;

class InviteEmployeeRequest extends FormRequest
{
    use CompanyInputRequest;

    private $employee;

    public function getEmployee()
    {
        return $this->employee = $this->employee ?:
            Employee::findOrFail($this->input('employee_id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        $employee = $this->getEmployee();

        return $this->authorizeCompanyAction(
            $employee->company_id, 
            'send register invitations'
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'invited_email' => ['required', 'string', 'email'],
            'expiry_time' => ['required', 'datetime'],
        ]);

        return $this->returnRules();
    }

    public function invitationData()
    {
        $data = $this->onlyInRules();
        $data['attachments'] = [
            'model' => 'App\Models\Employee',
            'model_id' => $this->getEmployee()->id,
            'related_column' => 'user_id',
            'role' => 'employee',
        ];

        return $data;
    }
}
