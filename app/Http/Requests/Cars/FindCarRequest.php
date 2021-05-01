<?php

namespace App\Http\Requests\Cars;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Car;

class FindCarRequest extends FormRequest
{
    private $car;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->car = Car::findOrFail(request()->input('id'));

        return true;
    }

    public function getCar()
    {
        return $this->car;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        return $rules;
    }
}
