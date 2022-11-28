<?php

namespace App\Http\Requests\Company\Analytics;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PopulateAnalyticsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->fresh()->can("view-any-analytic");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "start" => [
                "nullable", "date"
            ],
            "end" => [
                "nullable", "date"
            ],
            "recalculate" => [
                "nullable", "boolean"
            ],
            "timeframe" => [
                "nullable", "string"
            ],
            "group_by" => [
                "nullable", Rule::in([
                    "hourly", "daily", "monthly", "yearly"
                ])
            ]
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            "recalculate" => strtobool($this->get("recalculate"))
        ]);
    }
}
