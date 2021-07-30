<?php

namespace App\Http\Requests\Cars;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Car;

use App\Policies\CarPolicy;

class FindCarRequest extends FormRequest
{
    private $car;

    public function getCar()
    {
        if ($this->car) return $this->car;

        $id = $this->input('id') ?: $this->input('car_id');
        return $this->car = Car::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('view-car', $this->getCar());
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
