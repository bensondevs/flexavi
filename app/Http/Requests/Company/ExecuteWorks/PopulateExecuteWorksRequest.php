<?php

namespace App\Http\Requests\Company\ExecuteWorks;

use App\Models\{Appointment\Appointment, Work\Work};
use App\Traits\CompanyPopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class PopulateExecuteWorksRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

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

        return $this->work = Work::findOrFail($id);
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
        return $this->user()->fresh()->can('view-any-execute-work');
    }

    /**
     * Get options
     *
     * @return array
     */
    public function options()
    {
        if ($this->input('with_company')) {
            $this->addWith('company');
        }
        if ($this->input('with_appointment')) {
            $this->addWith('appointment');
        }
        if ($this->input('with_photos')) {
            $this->addWith('photos.works');
        }

        return $this->collectCompanyOptions();
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

    /**
     * Prepare for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        //
    }
}
