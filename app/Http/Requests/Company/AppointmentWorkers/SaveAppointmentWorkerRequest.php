<?php

namespace App\Http\Requests\Company\AppointmentWorkers;

use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;

class SaveAppointmentWorkerRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user()->fresh();
        if (!$this->isMethod('POST')) {
            $appointment = $this->getAppointmentWorker();
            return $user->can('edit-appointment-worker', $appointment);
        }

        return $user->can('create-appointment-worker');
    }

    /**
     * Get AppointmentWorker based on supplied input
     *
     * @return mixed
     */
    public function getAppointmentWorker()
    {
        // TODO: complete getAppointmentWorker logic
        // if ($this->worker) {
        //     return $this->worker;
        // }
        // $id = $this->input('id') ?: $this->input('appointment_worker_id');
        // return $this->worker = AppointmentWorker::findOrFail($id);

        return null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'appointment_id' => [
                'required',
                'string',
                'exists:appointments,id',
            ],
            'employee_type' => ['required', 'string'],
            'employee_id' => ['required', 'string', 'exists:employees,id'],
        ]);

        return $this->returnRules();
    }
}
