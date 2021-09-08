<?php

namespace App\Http\Requests\ExecuteWorks;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\InputRequest;

use App\Models\Work;
use App\Models\Appointment;

class ExecuteWorkRequest extends FormRequest
{
    use InputRequest;

    private $work;
    private $appointment;

    public function getWork()
    {
        if ($this->work) return $this->work;

        $id = $this->input('work_id');
        $work = Work::findOrFail($id);

        if ($appointment = $work->appointment) {
            $this->appointment = $appointment;
        }

        return $this->work = $work;
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
        $work = $this->getWork();
        $appointment = $this->getAppointment();

        return Gate::allows('execute-work', [$work, $appointment]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'appointment_id' => ['required', 'string'],
            'work_id' => ['required', 'string'],
            'description' => ['required', 'string'],
        ]);

        return $this->returnRules();
    }

    public function executeData()
    {
        $input = $this->validated();
        $input['company_id'] = $this->getAppointment()->company_id;
        return $input;
    }
}
