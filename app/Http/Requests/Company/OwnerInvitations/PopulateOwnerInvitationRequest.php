<?php

namespace App\Http\Requests\Company\OwnerInvitations;

use App\Enums\OwnerInvitation\OwnerInvitationStatus;
use App\Traits\CompanyPopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PopulateOwnerInvitationRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()
            ->fresh()
            ->can('view-any-invitation-owner');
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

        if ($status = $this->get('status')) {
            $this->addWhere([
                'column' => 'status',
                'value' => $status,
            ]);
        }

        return $this->collectCompanyOptions();
    }

    /**
     * Prepare input request before validation
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        //
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'status' => ['nullable' , Rule::in(OwnerInvitationStatus::getValues())]
        ];
    }
}
