<?php

namespace App\Http\Requests\Company\Cars;

use App\Models\Car\Car;
use App\Traits\RequestHasRelations;
use Illuminate\Foundation\Http\FormRequest;

class FindCarRequest extends FormRequest
{
    use RequestHasRelations;

    /**
     * List configuration for relationship loaded
     *
     * @var array
     */
    private $relationNames = [
        'with_worklists' => true,
        'with_company' => false,
        'with_registered_times' => true,
        'with_registered_time_employees' => true,
    ];

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
            ->can('view cars', $car);
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

        return $this->car = Car::withTrashed()->findOrFail($id);
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

    /**
     * Prepare inputs for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->prepareRelationInputs();
    }
}
