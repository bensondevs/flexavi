<?php

namespace App\Http\Requests\Company\Worklists\Appointments;

use App\Models\Worklist\Worklist;
use Illuminate\Foundation\Http\FormRequest;

class TruncateAppointmentsRequest extends FormRequest
{
    /**
     * Worklist object
     *
     * @var Worklist|null
     */
    private $worklist;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $worklist = $this->getWorklist();

        return $this->user()
            ->fresh()
            ->can('truncate-appointments-worklist', $worklist);
    }

    /**
     * Get Worklist based on supplied input
     *
     * @return Worklist
     */
    public function getWorklist()
    {
        if ($this->worklist) {
            return $this->worklist;
        }
        $id = $this->input('id') ?: $this->input('worklist_id');

        return $this->worklist = Worklist::findOrFail($id);
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
