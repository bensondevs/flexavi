<?php

namespace App\Http\Requests\Cars;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\InputRequest;

use App\Rules\Base64Image;

use App\Models\Car;

class SetCarImageRequest extends FormRequest
{
    use InputRequest;

    public $car;

    public function getCar()
    {
        if ($this->car) return $this->car;

        $id = $this->input('id') ?: $this->input('car_id');
        return $this->model = $this->car = Car::findOrFail($id);
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
        $this->setRules([
            'car_image' => ['required', 'file', 'max:5126', 'mimes:png,jpg,jpeg,svg'],
        ]);

        if (is_base64_string($this->input('car_image'))) {
            $this->rules['car_image'] = ['required', new Base64Image()];
        }

        return $this->returnRules();
    }
}
