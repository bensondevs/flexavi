<?php

namespace App\Http\Requests\Workdays\Appointments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Worklist;

class AttachManyAppointmentsRequest extends FormRequest
{
    private $worklist;

    public function getWorklist()
    {
        if ($this->worklist) return $this->worklist;

        $id = $this->input('worklist_id');
        return $this->worklist = Worklist::findOrFail($id);
    }

    protected function prepareForValidation()
    {
        $appointmentIds = $this->input('appointment_ids');
        if (is_string($appointmentIds)) {
            $appointmentIds = json_decode($appointmentIds, true);
            $this->merge(['appointment_ids' => $appointmentIds]);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $appointmentIds = $this->input('appointment_ids');
        return Gate::allows('attach-many-appointments-worklist', [$worklist, $appointmentIds]);
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
