<?php

namespace App\Http\Requests\Company\Workdays\Appointments;

use App\Models\Worklist\Worklist;
use Illuminate\Foundation\Http\FormRequest;

class AttachManyAppointmentsRequest extends FormRequest
{
    /**
     * Worklist object
     *
     * @var Worklist|null
     */
    private $worklist;

    /**
     * Get Worklist based on supplied input
     *
     * @return void
     */
    public function getWorklist()
    {
        if ($this->worklist) {
            return $this->worklist;
        }
        $id = $this->input('worklist_id');

        return $this->worklist = Worklist::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $appointmentIds = $this->input('appointment_ids');

        return $this->user()
            ->fresh()
            ->can('attach-many-appointments-worklist', [
                $this->worklist,
                $appointmentIds,
            ]);
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
        $appointmentIds = $this->input('appointment_ids');
        if (is_string($appointmentIds)) {
            $appointmentIds = json_decode($appointmentIds, true);
            $this->merge(['appointment_ids' => $appointmentIds]);
        }
    }
}
