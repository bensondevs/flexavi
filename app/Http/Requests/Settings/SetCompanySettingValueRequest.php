<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\{ Setting, SettingValue };

class SetCompanySettingValueRequest extends FormRequest
{
    /**
     * Target setting model container
     * 
     * @var \App\Models\Setting
     */
    private $setting;

    /**
     * Get setting based on supplied input of (`id` or `setting_id`) or `key`
     * 
     * @return \App\Models\Setting
     */
    public function getSetting()
    {
        if ($this->setting) return $this->setting;

        if ($this->has('id') || $this->has('setting_id')) {
            $id = $this->input('id', $this->input('setting_id'));
            return $this->setting = Setting::findOrFail($id);
        }

        if ($this->has('key')) {
            $key = $this->input('key');
            return $this->setting = Setting::findByKeyOrFail($key);
        }

        return abort(404, 'Unknown supplied input for selecting certain setting');
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $company = auth()->user()->company;
        return Gate::allows('set-company-setting-value', $company);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'value' => ['required', 'string'],
        ];
    }
}
