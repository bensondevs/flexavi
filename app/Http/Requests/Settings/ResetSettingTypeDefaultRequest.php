<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use BenSampo\Enum\Rules\EnumKey;

use App\Models\Company;
use App\Enums\Setting\SettingType;

class ResetSettingTypeDefaultRequest extends FormRequest
{
    /**
     * Target company of the request
     * 
     * @var \App\Models\Company|null
     */
    private $company;

    /**
     * Get company of the request
     * 
     * @return  \App\Models\Company|abort 404
     */
    public function getCompany()
    {
        if ($this->company) return $this->company;

        if ($this->has('company_id')) {
            $id = $this->input('company_id');
            return $this->company = Company::findOrFail($id);
        }

        $company = auth()->user()->company;
        return $this->company = $company;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $company = $this->getCompany();
        return Gate::allows('reset-default-company-setting-value', $company);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => ['required', 'numeric', new EnumKey(SettingType::class)]
        ];
    }
}
