<?php

namespace App\Http\Requests\Company\ExecuteWorks;

use App\Models\{Appointment\Appointment, ExecuteWork\ExecuteWork};
use App\Traits\InputRequest;
use Illuminate\Foundation\Http\FormRequest;

class MakeExecuteWorkContinuationRequest extends FormRequest
{
    use InputRequest;

    /**
     * Appointment object
     *
     * @var Appointment|null
     */
    private $appointment;

    /**
     * ExecuteWork object
     *
     * @var ExecuteWork|null
     */
    private $executeWork;

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

        return $this->user()
            ->fresh()
            ->can('make-continuation-execute-work', $parameters);
    }

    /**
     * Get ExecuteWork based on supplied input
     *
     * @return ExecuteWork
     */
    public function getExecuteWork()
    {
        if ($this->executeWork) {
            return $this->executeWork;
        }
        $id = $this->input('id');

        return $this->executeWork = ExecuteWork::findOrFail($id);
    }

    /**
     * Get Appointment based on supplied input
     *
     * @return Appointment
     */
    public function getAppointment()
    {
        if ($this->appointment) {
            return $this->appointment;
        }
        $id = $this->input('appointment_id');

        return $this->appointment = Appointment::findOrFail($id);
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
