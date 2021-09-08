<?php

namespace App\Http\Requests\Works;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\CompanyInputRequest;

use App\Models\Work;
use App\Models\Appointment;

class MarkWorkFinishRequest extends FormRequest
{
    use CompanyInputRequest;

    private $work;
    private $appointment;

    public function getWork()
    {
        if ($this->work) return $this->work;

        $id = $this->input('work_id');
        return $this->work = Work::with('appointments')->findOrFail($id);
    }

    public function getAppointment()
    {
        if ($this->appointment) return $this->appointment;

        $work = $this->getWork();
        $workAppointments = $work->appointments;
        if ($workAppointments->count() > 1) {
            $id = $this->input('appointment_id');
            return $this->appointment = Appointment::findOrFail($id);
        }

        $appointment = $workAppointments->first();
        return $this->appointment = $appointment;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $work = $this->getWork();
        return Gate::allows('mark-finish-work', $work);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'finish_note' => ['string'],
        ]);

        return $this->returnRules();
    }
}
