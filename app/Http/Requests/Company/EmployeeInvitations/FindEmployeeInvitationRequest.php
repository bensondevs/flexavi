<?php

namespace App\Http\Requests\Company\EmployeeInvitations;

use App\Models\Employee\EmployeeInvitation;
use App\Traits\{InputRequest, RequestHasRelations};
use Illuminate\Foundation\Http\FormRequest;

class FindEmployeeInvitationRequest extends FormRequest
{
    use InputRequest, RequestHasRelations;

    /**
     * List of loaded relation names
     *
     * @var array
     */
    protected $relationNames = [
    ];

    /**
     * SendEmployeeInvitation object
     *
     * @var EmployeeInvitation|null
     */
    private $employeeInvitation;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $employeeInvitation = $this->getEmployeeInvitation();
        return $this->user()
            ->fresh()
            ->can('view-invitation-employee', $employeeInvitation);
    }

    /**
     * Get SendEmployeeInvitation based on supplied input
     *
     * @return EmployeeInvitation
     */
    public function getEmployeeInvitation()
    {
        if ($this->employeeInvitation) {
            return $this->employeeInvitation;
        }
        $id = $this->input('id') ?: $this->input('employee_invitation_id');

        return $this->employeeInvitation = EmployeeInvitation::findOrFail($id);
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

    /**
     * Prepare inputtted data according to expected form
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->prepareRelationInputs();
    }
}
