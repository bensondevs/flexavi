<?php

namespace App\Http\Requests\Cars;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Car;

use App\Traits\RequestHasRelations;

class FindCarRequest extends FormRequest
{
    use RequestHasRelations;

    /**
     * List configuration for relationship loaded
     * 
     * @var array
     */
    private $relationNames = [
        'with_company' => true,
        'with_worklists' => true,
    ];

    /**
     * Found target car container
     * 
     * @var  \App\Models\Car|null
     */
    private $car;

    /**
     * Get requested car or abort 404
     * 
     * @return  \App\Models\Car|abort 404
     */
    public function getCar()
    {
        if ($this->car) return $this->car;

        $id = $this->input('id') ?: $this->input('car_id');
        return $this->car = Car::findOrFail($id);
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

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('view-car', $this->getCar());
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
