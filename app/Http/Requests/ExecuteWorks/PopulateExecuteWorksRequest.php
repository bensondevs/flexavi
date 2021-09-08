<?php

namespace App\Http\Requests\ExecuteWorks;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Work;
use App\Models\Appointment;

use App\Traits\CompanyPopulateRequestOptions;

class PopulateExecuteWorksRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    private $work;
    private $appointment;

    public function getWork()
    {
        if ($this->work) return $this->work;

        $id = $this->input('work_id');
        return $this->work = Work::findOrFail($id);
    }

    public function getAppointment()
    {
        if ($this->appointment) return $this->appointment;

        $id = $this->input('appointment_id');
        return $this->appointment = Appointment::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('view-any-execute-works');
    }

    protected function prepareForValidation()
    {
        if ($this->has('is_finished')) {
            $isFinished = $this->input('is_finished');
            $this->merge(['is_finished' => strtobool($isFinished)]);
        }

        if ($this->has('is_continuation')) {
            $isContinuation = $this->input('is_continuation');
            $this->merge(['is_continuation' => strtobool($isContinuation)]);
        }
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
        if ($this->has('work_id')) {
            $this->addWhere([
                'column' => 'work_id',
                'value' => $this->getWork()->id,
            ]);
        }

        if ($this->has('appointment_id')) {
            $this->addWhere([
                'column' => 'appointment_id',
                'value' => $this->getAppointment()->id,
            ]);
        }

        if ($this->has('is_finished')) {
            $this->addWhere([
                'column' => 'is_finished',
                'value' => $this->input('is_finished'),
            ]);
        }
        
        if ($this->has('is_continuation')) {
            $this->addWhere([
                'column' => 'is_continuation',
                'value' => $this->input('is_continuation'),
            ]);
        }

        if ($this->input('with_company')) {
            $this->addWith('company');
        }

        if ($this->input('with_appointment')) {
            $this->addWith('appointment');
        }

        if ($this->input('with_photos')) {
            $this->addWith('photos');
        }

        return $this->collectCompanyOptions();
    }
}
