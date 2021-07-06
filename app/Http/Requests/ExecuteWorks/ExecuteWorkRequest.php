<?php

namespace App\Http\Requests\ExecuteWorks;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\InputRequest;

use App\Models\Work;

class ExecuteWorkRequest extends FormRequest
{
    use InputRequest;

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
        $work = $this->getWork();
        $appointment = $this->getAppointment();

        return Gate::allows('execute-work', $work, $appointment);
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
        ]);

        return $this->returnRules();
    }

    public function executeData()
    {
        $input = $request->onlyInRules();
        $input['company_id'] = $this->getAppointment()->company_id;
        return $input;
    }
}
