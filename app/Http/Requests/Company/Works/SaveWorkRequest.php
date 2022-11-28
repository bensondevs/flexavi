<?php

namespace App\Http\Requests\Company\Works;

use App\Models\{Appointment\Appointment, Quotation\Quotation, Work\Work, WorkService\WorkService};
use App\Rules\FloatValue;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;

class SaveWorkRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Work object
     *
     * @var Work|null
     */
    private $work;

    /**
     * Quotation object
     *
     * @var Quotation|null
     */
    private $quotation;

    /**
     * Appointment object
     *
     * @var Appointment|null
     */
    private $appointment;

    /**
     * WorkService object
     *
     * @var WorkService|null
     */
    private $workService;

    /**
     * Get Quotation based on supplied input
     *
     * @return Quotation
     */
    public function getQuotation()
    {
        if ($this->quotation) {
            return $this->quotation;
        }
        $id = $this->input('quotation_id');

        return $this->quotation = Quotation::findOrFail($id);
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
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user()->fresh();
        if (!$this->isMethod('POST')) {
            return $user->can('edit-work', $this->getWork());
        }

        return $user->can('create-work');
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
        $id = $this->input('id');

        return $this->work = $this->model = Work::findOrFail($id);
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
            'work_service_id' => ['string'],
            'quantity' => ['required', 'integer'],
            'quantity_unit' => ['required', 'string'],
            'description' => ['required', 'string'],
            'unit_price' => ['required', new FloatValue(true)],
            'include_tax' => ['required', 'boolean'],

            'quotation_id' => [
                'required',
                'sometimes',
                'string',
                'required_without:appointment_id',
            ],
            'appointment_id' => [
                'required',
                'sometimes',
                'string',
                'required_without:quotation_id',
            ],
        ]);
        if ($this->input('include_tax')) {
            $this->addRule('tax_percentage', [
                'required',
                new FloatValue(true),
            ]);
        }

        return $this->returnRules();
    }

    /**
     * Prepare for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'company_id' => $this->getCompany()->id,
            'work_service_id' => $this->getWorkService()->id,
            'quantity' => (int)$this->input('quantity'),
            'unit_price' => (float)$this->input('unit_price'),
            'include_tax' => strtobool($this->input('include_tax')),
        ]);
        if ($this->input('include_tax')) {
            $this->merge([
                'tax_percentage' => (int)$this->input('tax_percentage'),
            ]);
        }
    }

    /**
     * Get WorkService based on supplied input
     *
     * @return WorkService
     */
    public function getWorkService()
    {
        if ($this->workService) {
            return $this->workService;
        }
        $id = $this->input('work_service_id');

        return $this->workService = WorkService::findOrFail($id);
    }
}
