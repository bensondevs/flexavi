<?php

namespace App\Http\Requests\Company\Settings;

use App\Models\Setting\CompanySetting;
use App\Traits\CompanyPopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class UpdateCompanySettingRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    /**
    * Setting instance container
    *
    * @return ?CompanySetting
    */
    private $setting;

    /**
     * Get setting model
     *
     * @return CompanySetting
     */
    public function getSetting(): CompanySetting
    {
        if ($this->setting) {
            return $this->setting;
        }
        $setting = CompanySetting::query()
            ->firstOrNew(['company_id' => $this->getCompany()->id]);

        if ($setting->wasRecentlyCreated) {
            $setting->fill(
                Arr::except(
                    CompanySetting::query()->default()->attributesToArray(),
                    ['id','company_id']
                )
            );
            $setting->save();
        }

        return $this->setting = $setting;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()
            ->fresh()
            ->can('edit-setting');
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
        if ($value = $this->get('auto_subs_same_plan_while_ends')) {
            $this->merge(['auto_subs_same_plan_while_ends' => strtobool($value)]);
        }
        if ($value = $this->get('invoicing_address_same_as_visiting_address')) {
            $this->merge(['invoicing_address_same_as_visiting_address' => strtobool($value)]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'auto_subs_same_plan_while_ends' => 'nullable|boolean',
            'invoicing_address_same_as_visiting_address' => 'nullable|boolean'
        ];
    }
}
