<?php

namespace App\Http\Requests\ScheduleCars;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\PopulateCompanyRequestOptions;

class PopulateCompanyScheduleCarsRequest extends FormRequest
{
    use PopulateCompanyRequestOptions;

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
        return [
            //
        ];
    }

    public function options()
    {
        $this->addWhere([
            'column' => 'schedule_id',
            'value' => $this->input('schedule_id'),
        ]);

        $this->addWith('car');

        return $this->collectOptions();
    }
}
