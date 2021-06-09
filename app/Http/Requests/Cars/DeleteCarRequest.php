<?php

namespace App\Http\Requests\Cars;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Car;

class DeleteCarRequest extends FormRequest
{
    private $car;

    public function getCar()
    {
        return $this->car = $this->car ?:
            Car::withTrashed()->findOrFail($this->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        $car = $this->getCar();

        return $user->hasCompanyPermission($car->company_id, 'delete cars');
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
