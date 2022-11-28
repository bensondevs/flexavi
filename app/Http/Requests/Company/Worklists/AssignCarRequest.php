<?php

namespace App\Http\Requests\Company\Worklists;

use App\Models\Car\Car;
use App\Models\Worklist\Worklist;
use Illuminate\Foundation\Http\FormRequest;

class AssignCarRequest extends FormRequest
{
    /**
     * Worklist object
     *
     * @var Worklist|null
     */
    private $worklist;

    /**
     * Car object
     *
     * @var Car|null
     */
    private $car;

    /**
     * Get Car based on supplied input
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
     * Get Worklist based on supplied input
     *
     * @return Worklist
     */
    public function getWorklist()
    {
        if ($this->worklist) {
            return $this->worklist;
        }
        $id = $this->input('id') ?: $this->input('worklist_id');

        return $this->worklist = Worklist::findOrFail($id);
    }

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
        return [];
    }
}
