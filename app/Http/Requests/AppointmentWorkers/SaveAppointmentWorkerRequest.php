<?php

namespace App\Http\Requests\AppointmentWorkers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\AppointmentWorker;

use App\Traits\CompanyInputRequest;

class SaveAppointmentWorkerRequest extends FormRequest
{
    use CompanyInputRequest;

    private $worker;

    public function getAppointmentWorker()
    {
        if ($this->worker) return $this->worker;

        $id = $this->input('id') ?: $this->input('appointment_worker_id');
        return $this->worker = AppointmentWorker::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {   
        if (! $this->isMethod('POST')) {
            $appointment = $this->getAppointmentWorker();
            return Gate::allows('edit-appointment-worker', $appointment);
        }

        return Gate::allows('create-appointment-worker');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'appointment_id' => ['required', 'string', 'exists:appointments,id'],
            'employee_type' => ['required', 'string'],
            'employee_id' => ['required', 'string', 'exists:employees,id'],
        ]);

        return $this->returnRules();
    }
}
