<?php

namespace App\Http\Requests\Company\Cars;

use App\Models\Car\Car;
use App\Rules\Helpers\Media;
use App\Traits\InputRequest;
use Illuminate\Foundation\Http\FormRequest;

class SetCarImageRequest extends FormRequest
{
    use InputRequest;

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

        return $this->user()
            ->fresh()
            ->can('set image cars', $car);
    }

    /**
     * Get Car based on the supplied input
     *
     * @return  Car
     */
    public function getCar()
    {
        if ($this->car) {
            return $this->car;
        }
        $id = $this->input('id') ?: $this->input('car_id');

        return $this->model = $this->car = Car::findOrFail($id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'car_image' => [
                'required',
                'image',
                'max:' . Media::MAX_IMAGE_SIZE,
                'mimes:' . Media::imageExtensions(),
            ],
        ]);

        return $this->returnRules();
    }
}
