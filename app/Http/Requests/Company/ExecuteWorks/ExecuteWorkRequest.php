<?php

namespace App\Http\Requests\Company\ExecuteWorks;

use App\Models\{Appointment\Appointment, Work\Work};
use App\Traits\InputRequest;
use Illuminate\Foundation\Http\FormRequest;

class ExecuteWorkRequest extends FormRequest
{
    use InputRequest;

    /**
     * Work model container
     *
     * @var Work|null
     */
    private $work;

    /**
     * Appointment model container
     *
     * @var Appointment|null
     */
    private $appointment;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $work = $this->getWork();
        $appointment = $this->getAppointment();

        return $this->user()
            ->fresh()
            ->can('execute-work', [$work, $appointment]);
    }

    /**
     * Get Work based on supplied input
     *
     * @return Work
     */
    public function getWork()
    {
        if ($this->work) {
            return $this->work;
        }
        $id = $this->input('work_id');
        $work = Work::findOrFail($id);
        if ($appointment = $work->appointment) {
            $this->appointment = $appointment;
        }

        return $this->work = $work;
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
            'work_id' => ['required', 'string'],
            'description' => ['required', 'string'],
        ]);

        return $this->returnRules();
    }

    /**
     * Collect the execution data
     *
     * @return array
     */
    public function executeData()
    {
        $input = $this->validated();
        $input['company_id'] = $this->getAppointment()->company_id;

        return $input;
    }
}
