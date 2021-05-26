<?php

namespace App\Http\Requests\Schedules;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\FloatValue;

use App\Traits\CompanyInputRequest;

class SaveScheduleRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'activity_name' => ['required', 'string'],
            'start' => ['required', 'datetime'],
            'end' => ['required', 'datetime'],
            'include_weekend' => ['required', 'boolean'],
            'start_money' => [new FloatValue(true)],
        ]);

        return $this->returnRules();
    }
}
