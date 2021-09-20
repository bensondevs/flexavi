<?php

namespace App\Http\Requests\Warranties;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\CompanyInputRequest;

use App\Models\Warranty;

class SaveWarrantyRequest extends FormRequest
{
    use CompanyInputRequest;

    private $warranty;
    private $work;
    private $appointment;

    public function getWarranty()
    {
        if ($this->warranty) {
            return $this->warranty;
        }

        if ($this->isMethod('POST')) {
            return $this->warranty = new Warranty;
        }

        $id = $this->input('id');
        $warranty = Warranty::with(['work', 'appointment'])->findOrFail($id);
        $this->work = $warranty->work;
        $this->appointment = $warranty->appointment;

        return $this->warranty = $warranty;
    }

    public function getWork()
    {
        if ($this->work) return $this->work;
    }

    public function getAppointment()
    {
        if ($appointmentId = $this->input('appointment_id')) {
            $appointment = Appointment::findOrFail($appointmentId);
            return $this->appointment = $appointment;
        }

        $warranty = $this->getWarranty();
        return $this->appointment = $warranty->appointment;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (! $this->isMethod('POST')) {
            $warranty = $this->getWarranty();
            return Gate::allows('edit-warranty', $warranty);
        }

        $appointment = $this->getAppointment();
        $work = $this->getWork();
        return Gate::allows('create-warranty', [$appointment, $work]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            //
        ]);

        return $this->returnRules();
    }
}
