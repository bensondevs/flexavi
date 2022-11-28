<?php

namespace App\Http\Requests\Company\HelpDesks;

use App\Enums\Notification\NotificationPopulateType;
use App\Enums\Notification\NotificationType;
use App\Traits\CompanyPopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PopulateHelpDesksRequest extends FormRequest
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
            ->can('view-any-help-desk');
    }

     /**
     * Get universal options
     *
     * @return array
     */
    public function options(): array
    {
        if ($keyword = $this->get('keyword', $this->get('search', null))) {
            $this->setSearch($keyword);
            $this->setSearchScope('table_scope_only');
        }

        return $this->collectOptions();
    }


    /**
     * Get company options
     *
     * @return array
     */
    public function companyOptions(): array
    {
        $this->options();
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
            'type' => ['nullable', Rule::in(NotificationType::getValues()) ],
            'types' => ['nullable', 'string'],
            'time' => ['nullable', Rule::in(NotificationPopulateType::getValues()) ],
        ];
    }
}
