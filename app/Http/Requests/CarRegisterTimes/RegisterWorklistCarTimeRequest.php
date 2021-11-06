<?php

namespace App\Http\Requests\CarRegisterTimes;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\{ Car, Worklist };

class RegisterWorklistCarTimeRequest extends FormRequest
{
    private $car;
    private $worklist;

    public function getCar()
    {
        if ($this->car) return $this->car;

        $id = $this->input('car_id');
        return $this->car = Car::findOrFail($id);
    }

    public function getWorklist()
    {
        if ($this->worklist) return $this->worklist;

        $id = $this->input('worklist_id');
        return $this->worklist = Worklist::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $car = $this->getCar();
        $worklist = $this->getWorklist();

        return Gate::allows('register-worklist-car-time', [$car, $worklist]);
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
