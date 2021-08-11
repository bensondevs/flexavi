<?php

namespace App\Http\Requests\Appointments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Worklist;

use App\Traits\CompanyPopulateRequestOptions;

class PopulateWorklistAppointmentsRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    private $worklist;

    public function getWorklist()
    {
        if ($this->worklist) return $this->worklist;

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
        $worklist = $this->getWorklist();
        return Gate::allows('view-any-appointment', $worklist);
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
        $this->addWhereHas('worklist', [
            [
                'column' => 'id',
                'value' => $this->getWorklist()->id,
            ]
        ]);

        return $this->collectOptions();
    }
}
