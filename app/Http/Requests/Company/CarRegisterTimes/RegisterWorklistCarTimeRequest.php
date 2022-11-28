<?php

namespace App\Http\Requests\Company\CarRegisterTimes;

use App\Models\{Car\Car, Worklist\Worklist};
use Illuminate\Foundation\Http\FormRequest;

class RegisterWorklistCarTimeRequest extends FormRequest
{
    /**
     * Car object
     *
     * @var Car|null
     */
    private $car;

    /**
     * Worklist object
     *
     * @var Worklist|null
     */
    private $worklist;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $car = $this->getCar();
        $worklist = $this->getWorklist();

        return $this->user()
            ->fresh()
            ->can('register worklist car times', [$car, $worklist]);
    }

    /**
     * Get Car based on the supplied input
     *
     * @return Car
     */
    public function getCar()
    {
        if ($this->car) {
            return $this->car;
        }
        $id = $this->input('car_id');

        return $this->car = Car::findOrFail($id);
    }

    /**
     * Get Worklist based on the supplied input
     *
     * @return Worklist
     */
    public function getWorklist()
    {
        if ($this->worklist) {
            return $this->worklist;
        }
        $id = $this->input('worklist_id');

        return $this->worklist = Worklist::findOrFail($id);
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
}
