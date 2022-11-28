<?php

namespace App\Http\Requests\Company\AppointmentWorkers;

use Illuminate\Foundation\Http\FormRequest;

class FindAppointmentWorkerRequest extends FormRequest
{
    /**
     * Get AppointmentWorker based on supplied input
     *
     * @return mixed
     */
    public function getWorker()
    {
        // TODO: complete getWorker logic
        // if ($this->worker) {
        //     return $this->worker;
        // }
        // $id = $this->input('id') ?: $this->input('appointment_worker_id');
        // return AppointmentWorker::findOrFail($id);

        return null;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $worker = $this->getAppointmentWorker();

        return $this->user()
            ->fresh()
            ->can('view-appointment-worker', $worker);
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
}
