<?php

namespace App\Http\Requests\CarRegisterTimeEmployees;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\{
    RequestHasRelations,
    CompanyPopulateRequestOptions
};

use App\Models\CarRegisterTime;

class PopulateCarRegisterTimeEmployeesRequest extends FormRequest
{
    use RequestHasRelations;
    use CompanyPopulateRequestOptions;

    private $relationNames = [
        'with_employee' => true,
    ];

    private $time;

    public function getCarRegisterTime()
    {
        if ($this->time) return $this->time;

        $id = $this->input('car_register_time_id');
        return $this->time = CarRegisterTime::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $time = $this->getCarRegisterTime();
        return Gate::allows('view-any-car-register-time-employee', $time);
    }

    protected function prepareForValidation()
    {
        $this->prepareRelationInputs();
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

    public function options()
    {
        return $this->collectCompanyOptions();
    }
}
