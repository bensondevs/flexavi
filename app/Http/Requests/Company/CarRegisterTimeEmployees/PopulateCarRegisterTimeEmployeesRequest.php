<?php

namespace App\Http\Requests\Company\CarRegisterTimeEmployees;

use App\Models\Car\CarRegisterTime;
use App\Traits\{CompanyPopulateRequestOptions, RequestHasRelations};
use Illuminate\Foundation\Http\FormRequest;

class PopulateCarRegisterTimeEmployeesRequest extends FormRequest
{
    use RequestHasRelations;
    use CompanyPopulateRequestOptions;

    /**
     * List configuration for relationship loaded
     *
     * @var array
     */
    private $relationNames = [
        'with_employee' => true,
    ];

    /**
     * CarRegisterTime object
     *
     * @var CarRegisterTime|null
     */
    private $time;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $time = $this->getCarRegisterTime();

        return $this->user()
            ->fresh()
            ->can('view any car register time employees', $time);
    }

    /**
     * Get CarRegisterTime based on supplied input
     *
     * @return CarRegisterTime
     */
    public function getCarRegisterTime()
    {
        if ($this->time) {
            return $this->time;
        }
        $id = $this->input('car_register_time_id');

        return $this->time = CarRegisterTime::findOrFail($id);
    }

    /**
     * Define request options
     *
     * @return array
     */
    public function options()
    {
        return $this->collectCompanyOptions();
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
