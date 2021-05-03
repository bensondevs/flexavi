<?php

namespace App\Http\Requests\Cars;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Car;

class SetCarImageRequest extends FormRequest
{
    public $car;

    public function getCar()
    {
        return $this->car ?: Car::findOrFail(
            request()->input('id')
        );
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
        return [
            'car_image' => ['required', 'file', 'max:5126', 'mimes:png,jpg,jpeg,svg'],
        ];
    }
}
