<?php

namespace App\Http\Requests\PaymentPickups;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use App\Traits\{
    RequestHasRelations,
    CompanyPopulateRequestOptions
};
use App\Models\{ Appointment, PaymentPickup };

class PopulateAppointmentPaymentPickupsRequest extends FormRequest
{
    use RequestHasRelations;
    use CompanyPopulateRequestOptions;

    /**
     * Found appointment model container
     * 
     * @var \App\Models\Appointment|null
     */
    private $appointment;

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
     * Get appointment from the supplied input of `appointment_id`
     * 
     * @return \App\Models\Appointment|abort 404
     */
    public function getAppointment()
    {
        if ($this->appointment) return $this->appointment;

        $id = $this->input('appointment_id');
        return $this->appointment = Appointment::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $appointment = $this->getAppointment();
        return Gate::allows('view-appointment-payment-pickup');
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
