<?php

namespace App\Http\Requests\Cars;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Car;
use App\Models\Company;

use App\Rules\AmongStrings;
use App\Rules\UniqueWithConditions;

class SaveCarRequest extends FormRequest
{
    private $car;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
        $rules = [
            'company_id' => ['required', 'string', 'exists:companies,id'],
            'brand' => ['required', 'string'],
            'model' => ['required', 'string'],
            'year' => ['required', 'integer', 'max:' . carbon()->now()->year],
        ];

        if (request()->input('status'))
            $rules['status'] = ['required', 'string', new AmongStrings(['free', 'out'])];

        if (request()->input('insured'))
            $rules['insured'] = ['required', 'boolean'];

        /*
            If on update
        */
        if (request()->isMethod('PUT') || request()->isMethod('PATCH')) {
            $this->car = Car::findOrFail(request()->input('id'));

            if ($this->car->car_name == request()->input('car_name'))
                $rules['car_name'] = ['required', 'string'];

            if ($this->car->car_license == request()->input('car_license'))
                $rules['car_license'] = ['required', 'string'];

            return $rules;
        }

        $rules['car_name'] = [
            'required', 
            'string', 
            new UniqueWithConditions(new Car, [
                'company_id' => request()->input('company_id'),

            ])
        ];
        $rules['car_license'] = [
            'required', 
            'string', 
            'unique:cars,car_license'
        ];

        return $rules;
    }

    public function onlyInRules()
    {
        return $this->only(array_keys($this->rules()));
    }
}
