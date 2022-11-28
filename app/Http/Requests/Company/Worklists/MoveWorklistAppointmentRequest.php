<?php

namespace App\Http\Requests\Company\Worklists;

use App\Models\{Appointment\Appointment, Worklist\Worklist};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MoveWorklistAppointmentRequest extends FormRequest
{
    /**
     * Found appointment model container
     *
     * @var Appointment|null
     */
    private $appointment;

    /**
     * Found worklist model container
     *
     * @var Worklist|null
     */
    private $fromWorklist;

    /**
     * Found worklist model container
     *
     * @var Worklist|null
     */
    private $toWorklist;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $appointment = $this->getAppointment();
        $fromWorklist = $this->getFromWorklist();
        $toWorklist = $this->getToWorklist();

        return $this->user()
            ->fresh()
            ->can('move-appointment-worklist', [
                $fromWorklist,
                $toWorklist,
                $appointment,
            ]);
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
     * Get From Worklist based on supplied input
     *
     * @return Worklist
     */
    public function getFromWorklist()
    {
        if ($this->fromWorklist) {
            return $this->fromWorklist;
        }
        $id = $this->input('from_worklist_id');

        return $this->fromWorklist = Worklist::find($id);
    }

    /**
     * Get To Worklist based on supplied input
     *
     * @return Worklist
     */
    public function getToWorklist()
    {
        if ($this->toWorklist) {
            return $this->toWorklist;
        }
        $id = $this->input('to_worklist_id');

        return $this->toWorklist = Worklist::find($id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'from_worklist_id' => [
                Rule::requiredIf(function () {
                    return $this->input('to_worklist_id') == '';
                }),
            ],
            'to_worklist_id' => [
                Rule::requiredIf(function () {
                    return $this->input('from_worklist_id') == '';
                }),
            ],
            'appointment_id' => [
                'required',
                Rule::exists('appointments', 'id'),
            ],
        ];
    }
}
