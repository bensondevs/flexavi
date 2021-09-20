<?php

namespace App\Http\Requests\Warranties;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\CompanyPopulateRequestOptions;

use App\Models\Work;
use App\Models\Appointment;

use App\Enums\Work\WorkStatus;

use App\Rules\CompanyRecordOwned;

class MultipleStoreRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    private $appointment;
    private $works;

    public function getAppointment()
    {
        if ($this->appointment) return $this->appointment;

        $id = $this->input('appointment_id');
        return $this->appointment = Appointment::findOrFail($id);
    }

    public function getWorks()
    {
        if ($this->works) return $this->works;

        $ids = $this->input('work_ids');
        return $this->works = Work::whereIn('id', $ids)->get();
    }

    protected function prepareForValidation()
    {
        if (is_string($this->input('work_ids'))) {
            $workIds = json_decode($this->input('work_ids'), true);
            $this->merge(['work_ids' => $workIds]);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $appointment = $this->getAppointment();
        $works = $this->getWorks();
        return Gate::allows('create-multiple-warranties', [$appointment, $works]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'appointment_id' => ['required'],

            'work_ids' => ['required', 'array'],
            'work_ids.*' => ['required', 'string'],
        ]);

        return $this->returnRules();
    }

    public function collectRawWarranties()
    {
        $rawWarranties = [];

        $appointment = $this->getAppointment();
        foreach ($this->getWorks() as $work) {
            $rawWarranties[] = [
                'company_id' => $appointment->company_id,
                'appointment_id' => $appointment->id,
                'work_id' => $work->id,
            ];
        }

        return $rawWarranties;
    }
}
