<?php

namespace App\Http\Requests\Company\Cars;

use App\Models\Car\Car;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;

class RestoreCarRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Trashed car object
     *
     * @var  Car|null
     */
    private $trashedCar;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $trashedCar = $this->getTrashedCar();

        return $this->user()
            ->fresh()
            ->can('restore cars', $trashedCar);
    }

    /**
     * Get Trashed Car based on the supplied input
     *
     * @return Car
     */
    public function getTrashedCar()
    {
        if ($this->trashedCar) {
            return $this->trashedCar;
        }
        $id = $this->input('id') ?: $this->input('car_id');

        return $this->trashedCar = Car::withTrashed()->findOrFail($id);
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
