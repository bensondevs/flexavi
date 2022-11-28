<?php

namespace App\Http\Requests\Company\CarRegisterTimes;

use App\Models\Car\CarRegisterTime;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;

class DeleteCarRegisterTimeRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * CarRegisterTime object
     *
     * @var CarRegisterTime|null
     */
    private $time;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $time = $this->getCarRegisterTime();
        $permissionName =
            ($this->input('force') ? 'force ' : '') .
            'delete car register times';

        return $this->user()
            ->fresh()
            ->can($permissionName, $time);
    }

    /**
     * Get CarRegisterTime based on the supplied input
     *
     * @return CarRegisterTime
     */
    public function getCarRegisterTime()
    {
        if ($this->time) {
            return $this->time;
        }
        $id = $this->input('car_register_time_id') ?: $this->input('id');

        return $this->time = CarRegisterTime::withTrashed()->findOrFail($id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Prepare inputs for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($force = $this->input('force')) {
            $this->merge(['force' => strtobool($force)]);
        }
    }
}
