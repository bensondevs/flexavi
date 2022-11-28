<?php

namespace App\Http\Requests\Company\Cars;

use App\Models\Car\Car;
use Illuminate\Foundation\Http\FormRequest;

class DeleteCarRequest extends FormRequest
{
    /**
     * Car object
     *
     * @var  Car|null
     */
    private $car;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $car = $this->getCar();
        $user = $this->user()->fresh();

        return $this->force
            ? $user->can('force delete cars', $car)
            : $user->can('delete cars', $car);
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
        $car = new Car();
        if ($this->input('force')) {
            $car = $car->withTrashed();
        }
        $id = $this->input('id') ?: $this->input('car_id');

        return $this->car = $car->findOrFail($id);
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
