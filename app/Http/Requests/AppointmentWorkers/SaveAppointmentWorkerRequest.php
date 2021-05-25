<?php

namespace App\Http\Requests\AppointmentWorkers;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\AppointmentWorker;

use App\Traits\CompanyInputRequest;

class SaveAppointmentWorkerRequest extends FormRequest
{
    use CompanyInputRequest;

    private $worker;

    public function getWorker()
    {
        return $this->worker = $this->model = $this->worker ?:
            AppointmentWorker::findOrFail($this->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->authorizeCompanyAction('appointment workers');
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
