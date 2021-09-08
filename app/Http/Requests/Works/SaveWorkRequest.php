<?php

namespace App\Http\Requests\Works;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Rules\FloatValue;

use App\Models\Work;
use App\Models\Quotation;
use App\Models\WorkContract;
use App\Models\Appointment;

use App\Traits\CompanyInputRequest;

class SaveWorkRequest extends FormRequest
{
    use CompanyInputRequest;

    private $work;

    private $quotation;
    private $appointment;

    public function getWork()
    {
        if ($this->work) return $this->work;

        $id = $this->input('id');
        return $this->work = $this->model = Work::findOrFail($id);
    }

    public function getQuotation()
    {
        if ($this->quotation) return $this->quotation;

        $id = $this->input('quotation_id');
        return $this->quotation = Quotation::findOrFail($id);
    }

    public function getAppointment()
    {
        if ($this->appointment) return $this->appointment;

        $id = $this->input('appointment_id');
        return $this->appointment = Appointment::findOrFail($id);
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'company_id' => $this->getCompany()->id,

            'quantity' => (int) $this->input('quantity'),
            'unit_price' => (float) $this->input('unit_price'),
            'include_tax' => strtobool($this->input('include_tax')),
        ]);

        if ($this->input('include_tax')) {
            $this->merge(['tax_percentage' => (int) $this->input('tax_percentage')]);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (! $this->isMethod('POST')) {
            return Gate::allows('edit-work', $this->getWork());
        }

        return Gate::allows('create-work');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'company_id' => ['string'],

            'quantity' => ['required', 'integer'],
            'quantity_unit' => ['required', 'string'],
            'description' => ['required', 'string'],
            'unit_price' => ['required', new FloatValue(true)],
            'include_tax' => ['required', 'boolean'],

            'appointment_id' => ['required', 'string', 'required_without:quotation_id'],
            'quotation_id' => ['required', 'string', 'required_without:appointment_id'],
        ]);

        if ($this->input('include_tax')) {
            $this->addRule('tax_percentage', ['required', new FloatValue(true)]);
        }

        return $this->returnRules();
    }
}
