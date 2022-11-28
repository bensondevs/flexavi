<?php

namespace App\Http\Requests\Company\PendingInvitations;

use App\Enums\RegisterInvitation\RegisterInvitationStatus;
use App\Traits\CompanyInputRequest;
use App\Traits\PopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class PendingInvitationEmployeeRequest extends FormRequest
{
    use PopulateRequestOptions, CompanyInputRequest;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()
            ->fresh()
            ->can('view-pending-invitation-employee');
    }

    /**
     * Get options
     *
     * @return array
     */
    public function options()
    {
        $company = $this->getCompany();
        $this->setWheres([
            [
                'column' => 'status',
                'value' => RegisterInvitationStatus::Active,
            ],
        ]);
        $this->addWhereJsonContains([
            'column' => 'attachments',
            'inside_column' => 'company_id',
            'value' => $company->id,
        ]);

        return $this->collectOptions();
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
