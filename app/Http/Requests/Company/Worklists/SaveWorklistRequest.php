<?php

namespace App\Http\Requests\Company\Worklists;

use App\Models\{Car\Car, Workday\Workday, Worklist\Worklist};
use App\Traits\CompanyInputRequest;
use App\Traits\InputRequest;
use Illuminate\Foundation\Http\FormRequest;

class SaveWorklistRequest extends FormRequest
{
    use InputRequest, CompanyInputRequest;

    /**
     * Workday object
     *
     * @var Workday|null
     */
    private $workday;

    /**
     * Worklist object
     *
     * @var Worklist|null
     */
    private $worklist;

    /**
     * Car object
     *
     * @var Car|null
     */
    private $car;

    /**
     * Get Car based on supplied input
     *
     * @return Car
     */
    public function getCar()
    {
        if ($this->car) {
            return $this->car;
        }
        $id = $this->input('car_id');

        return $this->car = Car::findOrFail($id);
    }

    /**
     * Prepare for validation.
     *
     * @return void
     */
    public function prepareForValidation()
    {
        if (!is_array($this->input('employee_ids'))) {
            $employeeIds = $this->input('employee_ids');
            $employeeIds = json_decode($employeeIds, true);
            $this->merge(['employee_ids' => $employeeIds]);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user()->fresh();
        if (!$this->isMethod('POST')) {
            $worklist = $this->getWorklist();
            return $user->can('edit-worklist', $worklist);
        }
        $workday = $this->getWorkday();

        return $user->can('create-worklist', $workday);
    }

    /**
     * Get Worklist based on supplied input
     *
     * @return Worklist
     */
    public function getWorklist()
    {
        if ($this->worklist) {
            return $this->worklist;
        }
        $id = $this->input('id') ?: $this->input('worklist_id');
        $worklist = Worklist::findOrFail($id);
        $this->worklist = $worklist;
        $this->workday = $worklist->workday;

        return $worklist;
    }

    /**
     * Get Workday based on supplied input
     *
     * @return Workday
     */
    public function getWorkday()
    {
        if ($this->workday) {
            return $this->workday;
        }

        $id = $this->input('workday_id');

        if (is_null($id)) {
            $date = $this->input('workday_date');
            return $this->workday = Workday::where('company_id', $this->getCompany()->id)
                ->where('date', $date)
                ->firstOrFail();
        }

        return $this->workday = Workday::findOrFail($id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'worklist_name' => ['nullable', 'string'],
            'user_id' => ['required', 'string', 'exists:users,id'], // represent of pic
            'car_id' => ['required', 'string', 'exists:cars,id'],
            'workday_id' => ['required_without:workday_date'],
            'workday_date' => ['required_without:workday_id'],
            'employee_ids' => ['required', 'array'],
            'employee_ids.*' => ['required', 'string', 'exists:users,id'],
        ]);

        return $this->returnRules();
    }

    public function worklistData()
    {
        $input = $this->validated();
        $input['workday_id'] = $this->workday
            ? $this->workday->id
            : $this->worklist->workday_id;
        $input['company_id'] = $this->workday
            ? $this->workday->company_id
            : $this->worklist->company_id;

        return $input;
    }
}
