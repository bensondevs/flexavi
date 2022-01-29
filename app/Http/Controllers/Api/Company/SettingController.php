<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Settings\{
    PopulateCompanySettingsRequest as PopulateRequest,
    SetCompanySettingValueRequest as SetValueRequest,
    ResetSettingTypeDefaultRequest as ResetTypeDefaultRequest,
    ResetAllSettingDefaultRequest as ResetAllDefaultRequest
};
use App\Http\Resources\{
    SettingResource, SettingValueResource
};
use App\Models\SettingValue;

use App\Repositories\SettingRepository;

class SettingController extends Controller
{
    /**
     * Setting repository class container
     * 
     * @var \App\Repositories\SettingRepository|null
     */
    private $setting;

    /**
     * Controller constructor method
     * 
     * @return void
     */
    public function __construct(SettingRepository $setting)
    {
        $this->setting = $setting;
    }

    /**
     * Populate all setting types with current company values
     * of the setting
     * 
     * @param  PopulateRequest  $request
     * @return \Illuminate\Support\Facades\Response
     */
    public function companySettings(PopulateRequest $request)
    {
        $company = $request->getCompany();
        $values = SettingValue::where('company_id', $company->id)
            ->with('setting')
            ->get();

        return response()->json(['values' => $values]);
    }

    /**
     * Set value to setting key
     * 
     * @param  SetValueRequest  $request
     * @return \Illuminate\Support\Facades\Response
     */
    public function setValue(SetValueRequest $request)
    {
        $setting = $request->getSetting();
        $this->setting->setModel($setting);
        
        $company = $request->getCompany();
        $this->setting->setCompany($company);

        $value = $request->input('value');
        $this->setting->setValue($value);

        return apiResponse($this->setting);
    }

    /**
     * Reset value to default for a certain type of setting
     * 
     * @param  ResetTypeDefaultRequest  $request
     * @return \Illuminate\Support\Facades\Response
     */
    public function resetTypeDefault(ResetTypeDefaultRequest $request)
    {
        $type = $request->input('type');
        $company = $request->getCompany();

        $this->setting->setCompany($company);
        $this->setting->resetDefault($type);

        return apiResponse($this->setting);
    }

    /**
     * Reset all settings of company to default value
     * 
     * @return \Illuminate\Support\Facades\Response
     */
    public function resetAllDefault()
    {
        $user = auth()->user();
        $company = $user->company;
        if (\Gate::forUser($user)->denies('reset-default-company-setting-value', $company)) {
            return abort(403, 'You have no permission to reset all company setting values.');
        }

        $this->setting->setCompany($company);
        $this->setting->resetAllDefault();

        return apiResponse($this->setting);
    }
}