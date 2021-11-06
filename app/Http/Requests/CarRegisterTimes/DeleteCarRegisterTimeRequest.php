<?php

namespace App\Http\Requests\CarRegisterTimes;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\CompanyInputRequest;

use App\Models\CarRegisterTime;

class DeleteCarRegisterTimeRequest extends FormRequest
{
    use CompanyInputRequest;

    private $time;

    public function getCarRegisterTime()
    {
        if ($this->time) return $this->time;

        $id = $this->input('car_register_time_id') ?: $this->input('id');
        return $this->time = CarRegisterTime::withTrashed()->findOrFail($id);
    }

    protected function prepareForValidation()
    {
        $force = strtobool($this->input('force'));
        $this->merge(['force' => $force]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $time = $this->getCarRegisterTime();
        $permissionName = ($this->input('force') ? 'force-' : '') . 'delete-car-register-time';
        return Gate::allows($permissionName, $time);
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
}
