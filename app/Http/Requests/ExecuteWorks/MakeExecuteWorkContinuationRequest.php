<?php

namespace App\Http\Requests\ExecuteWorks;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\InputRequest;

use App\Models\Work;
use App\Models\ExecuteWork;
use App\Models\Appointment;

class MakeExecuteWorkContinuationRequest extends FormRequest
{
    use InputRequest;

    private $appointment;
    private $work;
    private $executeWork;

    public function getExecuteWork()
    {
        if ($this->executeWork) return $this->executeWork;

        $id = $this->input('id');
        return $this->executeWork = ExecuteWork::findOrFail($id);
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
        $executeWork = $this->getExecuteWork();
        $appointment = $this->getAppointment();

        $parameters = [$executeWork, $appointment];
        return Gate::allows('make-continuation-execute-work', $parameters);
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
            'note' => ['string'],
        ]);

        return $this->returnRules();
    }
}
