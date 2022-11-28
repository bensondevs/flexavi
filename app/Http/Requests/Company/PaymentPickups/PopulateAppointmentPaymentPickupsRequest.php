<?php

namespace App\Http\Requests\Company\PaymentPickups;

use App\Models\Appointment\Appointment;
use App\Traits\{CompanyPopulateRequestOptions, RequestHasRelations};
use Illuminate\Foundation\Http\FormRequest;

class PopulateAppointmentPaymentPickupsRequest extends FormRequest
{
    use RequestHasRelations;
    use CompanyPopulateRequestOptions;

    /**
     * List of relationships that will be loaded
     * Set the attribute to true, it will load the relationship
     * upon the response
     *
     * @var array
     */
    protected $relationNames = [
        'with_appointment' => false,
        'with_employee' => true,
    ];
    /**
     * Found appointment model container
     *
     * @var Appointment|null
     */
    private $appointment;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $appointment = $this->getAppointment();

        return $this->user()
            ->fresh()
            ->can('view-appointment-payment-pickup', $appointment);
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
