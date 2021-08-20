<?php

namespace App\Http\Requests\Workdays\Appointments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Worklist;

class TruncateAppointmentsRequest extends FormRequest
{
    private $worklist;

    public function getWorklist()
    {
        if ($this->worklist) return $this->worklist;

        $id = $this->input('id') ?: $this->input('worklist_id');
        return $this->worklist = Worklist::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $worklist = $this->getWorklist();
        return Gate::allows('truncate-appointments-worklist', $worklist);
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
