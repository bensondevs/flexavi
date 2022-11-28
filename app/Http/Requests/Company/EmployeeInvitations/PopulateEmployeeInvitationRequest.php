<?php

namespace App\Http\Requests\Company\EmployeeInvitations;

use App\Enums\EmployeeInvitation\EmployeeInvitationStatus;
use App\Traits\{CompanyPopulateRequestOptions};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PopulateEmployeeInvitationRequest extends FormRequest
{
    use  CompanyPopulateRequestOptions;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()
            ->fresh()
            ->can('view-any-pending-invitation-employee');
    }

    /**
     * Set options for get() query
     *
     * @return array
     */
    public function options(): array
    {
        if ($keyword = $this->get('keyword', $this->get('search', null))) {
            $this->setSearch($keyword);
            $this->setSearchScope('table_scope_only');
        }

        if ($status =  $this->get('status')) {
            $this->addWhere([
                'column' => 'status',
                'value' => $status,
            ]);
        }

        return $this->collectCompanyOptions();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'status' => ['nullable' , Rule::in(EmployeeInvitationStatus::getValues())],
        ];
    }
}
