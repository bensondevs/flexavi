<?php

namespace App\Http\Requests\Worklists;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\InputRequest;

use App\Models\Workday;
use App\Models\Worklist;

class SaveWorklistRequest extends FormRequest
{
    use InputRequest;

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

        $id = $this->input('id') ?: $this->input('worklist_id');
        $worklist = Worklist::findOrFail($id);
        $this->worklist = $worklist;
        $this->workday = $worklist->workday;

        return $worklist;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (! $this->isMethod('POST')) {
            $worklist = $this->getWorklist();
            return Gate::allows('edit-worklist', $worklist);
        }

        $workday = $this->getWorkday();
        return Gate::allows('create-worklist', $workday);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'worklist_name' => ['required', 'string'],
        ]);

        return $this->returnRules();
    }

    public function worklistData()
    {
        $input = $this->validated();
        $input['workday_id'] = $this->workday ? 
            $this->workday->id : 
            $this->worklist->workday_id;
        $input['company_id'] = $this->workday ?
            $this->workday->company_id :
            $this->worklist->company_id;

        return $input;
    }
}
