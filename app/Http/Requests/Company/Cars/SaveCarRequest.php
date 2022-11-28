<?php

namespace App\Http\Requests\Company\Cars;

use App\Models\{Car\Car};
use App\Rules\{MoneyValue, UniqueWithConditions};
use App\Rules\Helpers\Media;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;

class SaveCarRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Car object
     *
     * @var  Car|null
     */
    private $car;

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

        return $this->car = $this->model = Car::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()
            ->fresh()
            ->can('create cars');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'brand' => ['required', 'string'],
            'model' => ['required', 'string'],
            'year' => ['required', 'integer', 'max:' . carbon()->now()->year],
            'car_name' => [
                'required',
                'string',
                new UniqueWithConditions(new Car(), [
                    'company_id' => $this->input('company_id'),
                ]),
            ],
            'car_license' => ['required', 'string', 'unique:cars,car_license'],
            'car_image' => [
                'required',
                'image',
                'max:' . Media::MAX_IMAGE_SIZE,
                'mimes:' . Media::imageExtensions(),
            ],
            'insured' => ['boolean'],
            'insurance_tax' => ['required_if:insured,==,1', new MoneyValue()],
            'max_passanger' => ['nullable', 'numeric'],
            'apk' => ['required', 'date_format:Y-m-d H:i:s']
        ]);

        return $this->returnRules();
    }
}
