<?php

namespace App\Http\Requests\Cars;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Car;

class DeleteCarRequest extends FormRequest
{
    private $car;

    public function getCar()
    {
        if ($this->car) return $this->car;

        $car = new Car();
        if ($this->input('force')) {
            $car = $car->withTrashed();
        }

        $id = $this->input('id') ?: $this->input('car_id');
        return $this->car = $car->findOrFail($id);
    }

    protected function prepareForValidation()
    {
        if ($force = $this->input('force')) {
            $this->merge(['force' => strtobool($force)]);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $car = $this->getCar();
        return ($this->force) ? 
            Gate::allows('force-delete-car', $car) :
            Gate::allows('delete-car', $car);
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
