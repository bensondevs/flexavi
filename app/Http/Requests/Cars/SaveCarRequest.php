<?php

namespace App\Http\Requests\Cars;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Car;
use App\Models\Company;

use App\Rules\AmongStrings;
use App\Rules\UniqueWithConditions;

use App\Traits\CompanyInputRequest;

class SaveCarRequest extends FormRequest
{
    use CompanyInputRequest;

    private $car;

    public function getCar()
    {
        return $this->car = $this->model = $this->car ?:
            Car::findOrFail($this->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->authorizeCompanyAction('cars');
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
            'insured' => ['boolean'],
            'car_name' => ['required', 'string', new UniqueWithConditions(
                new Car, 
                [
                    'company_id' => $this->input('company_id'),
                ]
            )],
            'car_license' => ['required', 'string', 'unique:cars,car_license'],
        ]);

        return $this->returnRules();
    }
}
