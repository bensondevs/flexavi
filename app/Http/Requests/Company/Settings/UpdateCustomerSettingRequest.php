<?php

namespace App\Http\Requests\Company\Settings;

use App\Models\Setting\CustomerSetting;
use App\Traits\CompanyPopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class UpdateCustomerSettingRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    /**
     * Setting instance container
     *
     * @return ?CustomerSetting
     */
    private $setting;

    /**
     * Get setting model
     *
     * @return CustomerSetting
     */
    public function getSetting(): CustomerSetting
    {
        if ($this->setting) {
            return $this->setting;
        }
        $setting = CustomerSetting::query()
            ->firstOrNew(['company_id' => $this->getCompany()->id]);

        if ($setting->wasRecentlyCreated) {
            $setting->fill(
                Arr::except(
                    CustomerSetting::query()->default()->attributesToArray(),
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
            'pagination' => 'nullable|numeric',
        ];
    }
}
