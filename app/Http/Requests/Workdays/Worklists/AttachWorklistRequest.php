<?php

namespace App\Http\Requests\Workdays\Worklists;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Workday;
use App\Models\Worklist;

class AttachWorklistRequest extends FormRequest
{
    private $workday;
    private $worklist;

    public function getWorkday()
    {
        if ($this->workday) return $this->workday;

        $id = $this->input('workday_id');
        return $this->workday = Workday::findOrFail($id);
    }

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
        $workday = $this->getWorkday();
        $worklist = $this->getWorklist();

        return Gate::allows('attach-worklist-workday', [$worklist, $workday]);
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
