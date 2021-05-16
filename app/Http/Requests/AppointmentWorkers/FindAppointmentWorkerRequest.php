<?php

namespace App\Http\Requests\AppointmentWorkers;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\AppointmentWorker;

class FindAppointmentWorkerRequest extends FormRequest
{
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
        return true;
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
