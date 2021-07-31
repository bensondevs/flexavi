<?php

namespace App\Http\Requests\AppointmentWorkers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\AppointmentWorker;

class FindAppointmentWorkerRequest extends FormRequest
{
    private $worker;

    public function getWorker()
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
        $worker = $this->getAppointmentWorker();
        return Gate::allows('view-appointment-worker', $worker);
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
}
