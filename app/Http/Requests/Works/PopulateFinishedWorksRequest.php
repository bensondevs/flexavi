<?php

namespace App\Http\Requests\Works;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Quotation;
use App\Models\Appointment;

use App\Enums\Work\WorkStatus;

use App\Traits\PopulateRequestOptions;

class PopulateFinishedWorksRequest extends FormRequest
{
    use PopulateRequestOptions;

    private $attachable;
    private $quotation;
    private $appointment;

    public function getQuotation()
    {
        if ($this->quotation) return $this->quotation;

        $id = $this->input('quotation_id');
        return $this->attachable = $this->quotation = Quotation::find($id);
    }

    public function getAppointment()
    {
        if ($this->appointment) return $this->appointment;

        $id = $this->input('appointment_id');
        return $this->attachable = $this->appointment = Appointment::find($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $attachable = ($this->input('quotation_id')) ?
            $this->getQuotation() :
            $this->getAppointment();
        return Gate::allows('view-any-work', $attachable);
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

    public function options()
    {
        $attachedTo = $this->input('attached_to');

        if ($attachedTo == 'quotation') {
            $this->addWhere([
                'column' => 'quotation_id',
                'operator' => '=',
                'value' => $this->getQuotation()->id,
            ]);
        } else {
            $this->addWhere([
                'column' => 'appointment_id',
                'operator' => '=',
                'value' => $this->getAppointment()->id,
            ]);
        }

        $this->addWhere([
            'column' => 'status',
            'operator' => '=',
            'value' => WorkStatus::Finished,
        ]);

        return $this->collectOptions();
    }
}
