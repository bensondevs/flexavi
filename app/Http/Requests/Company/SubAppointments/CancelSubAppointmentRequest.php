<?php

namespace App\Http\Requests\Company\SubAppointments;

use App\Enums\SubAppointment\SubAppointmentCancellationVault;
use App\Models\Appointment\SubAppointment;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;

class CancelSubAppointmentRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * SubAppointment object
     *
     * @var SubAppointment|null
     */
    private $subAppointment;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $subAppointment = $this->getSubAppointment();

        return $this->user()
            ->fresh()
            ->can('cancel-sub-appointment', $subAppointment);
    }

    /**
     * Get SubAppointment based on supplied input
     *
     * @return SubAppointment
     */
    public function getSubAppointment()
    {
        if ($this->subAppointment) {
            return $this->subAppointment;
        }
        $id = $this->input('id') ?: $this->input('sub_appointment_id');

        return $this->subAppointment = SubAppointment::findOrFail($id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'cancellation_cause' => ['required', 'string'],
            'cancellation_vault' => [
                'required',
                'min:' . SubAppointmentCancellationVault::Roofer,
                'max:' . SubAppointmentCancellationVault::Customer,
            ],
            'cancellation_note' => ['string'],
        ]);

        return $this->returnRules();
    }
}
