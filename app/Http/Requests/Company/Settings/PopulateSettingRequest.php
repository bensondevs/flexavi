<?php

namespace App\Http\Requests\Company\Settings;

use App\Enums\Setting\SettingModule;
use App\Traits\CompanyPopulateRequestOptions;
use App\Traits\PopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PopulateSettingRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;
    use PopulateRequestOptions;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()
            ->fresh()
            ->can('view-any-setting');
    }

    /**
     * Prepare input for validation.
     *
     * This will prepare input to configure the loadable relationships
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        //
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
