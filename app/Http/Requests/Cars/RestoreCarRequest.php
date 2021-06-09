<?php

namespace App\Http\Requests\Cars;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\CompanyInputRequest;

use App\Models\Car;

class RestoreCarRequest extends FormRequest
{
    use CompanyInputRequest;

    private $trashedCar;

    public function getTrashedCar()
    {
        return $this->trashedCar = $this->trashedCar ?:
            Car::withTrashed()->findOrFail($this->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $car = $this->getTrashedCar();
        return $this->checkCompanyPermission('restore cars', $car);
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
