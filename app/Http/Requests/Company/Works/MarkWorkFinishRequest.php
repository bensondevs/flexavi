<?php

namespace App\Http\Requests\Company\Works;

use App\Models\{Appointment\Appointment, Work\Work};
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;

class MarkWorkFinishRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Work object
     *
     * @var Work|null
     */
    private $work;

    /**
     * Appointment object
     *
     * @var Appointment|null
     */
    private $appointment;

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

        return $this->work = Work::with('appointments')->findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $work = $this->getWork();

        return $this->user()
            ->fresh()
            ->can('mark-finish-work', $work);
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
