<?php

namespace App\Http\Requests\Cars;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\CompanyInputRequest;

use App\Models\Car;

class RestoreCarRequest extends FormRequest
{
    use CompanyInputRequest;

    private $trashedCar;

    public function getTrashedCar()
    {
        if ($this->trashedCar) return $this->trashedCar;

        $id = $this->input('id') ?: $this->input('car_id');
        return $this->trashedCar = Car::withTrashed()->findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $car = $this->getTrashedCar();
        return Gate::allows('restore-car', $car);
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
