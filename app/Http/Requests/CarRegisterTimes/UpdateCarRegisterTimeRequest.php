<?php

namespace App\Http\Requests\CarRegisterTimes;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\CompanyInputRequest;

use App\Models\{ CarRegisterTime };

class UpdateCarRegisterTimeRequest extends FormRequest
{
    use CompanyInputRequest;

    private $time;

    public function getCarRegisterTime()
    {
        if ($this->time) return $this->time;

        $id = $this->input('car_register_time_id') ?: $this->input('id');
        return $this->time = CarRegisterTime::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $time = $this->getCarRegisterTime();
        return Gate::allows('update-car-register-time', $time);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'should_out_at' => ['datetime'],
            'should_return_at' => ['datetime'],

            'marked_out_at' => ['datetime'],
            'marked_return_at' => ['datetime'],
        ]);

        return $this->returnRules();
    }
}
