<?php

namespace App\Http\Requests\Worklists;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Worklist;

class RestoreWorklistRequest extends FormRequest
{
    private $worklist;

    public function getTrashedWorklist()
    {
        if ($this->worklist) return $this->worklist;

        $id = $this->input('id') ?: $this->input('worklist_id');
        return $this->worklist = Worklist::onlyTrashed()->findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $worklist = $this->getTrashedWorklist();
        return Gate::allows('restore-worklist', $worklist);
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
