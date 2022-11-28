<?php

namespace App\Http\Requests\Company\Settings;

use App\Enums\Setting\DashboardSetting\DashboardResultGraph;
use App\Models\Setting\DashboardSetting;
use App\Traits\CompanyPopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class UpdateDashboardSettingRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    /**
     * Setting instance container
     *
     * @return ?DashboardSetting
     */
    private $setting;

    /**
     * Get setting model
     *
     * @return DashboardSetting
     */
    public function getSetting(): DashboardSetting
    {
        if ($this->setting) {
            return $this->setting;
        }
        $setting = DashboardSetting::query()
            ->firstOrNew(['company_id' => $this->getCompany()->id]);

        if ($setting->wasRecentlyCreated) {
            $setting->fill(
                Arr::except(
                    DashboardSetting::query()->default()->attributesToArray(),
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
        if ($value = $this->get('result_graph')) {
            $this->merge(['result_graph' => (int) $value]);
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
            'result_graph' => ['nullable',Rule::in(DashboardResultGraph::getValues())],
            'invoice_revenue_date_range' => 'nullable',
            'best_selling_service_date_range' => 'nullable'
        ];
    }
}
