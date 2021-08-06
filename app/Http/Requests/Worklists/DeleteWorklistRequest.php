<?php

namespace App\Http\Requests\Worklists;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Worklist;

class DeleteWorklistRequest extends FormRequest
{
    private $worklist;

    public function getWorklist()
    {
        if ($this->worklist) return $this->worklist;

        $id = $this->input('id') ?: $this->input('worklist_id');
        return $this->worklist = Worklist::withTrashed()->findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $worklist = $this->getWorklist();

        if ($force = $this->input('force')) {
            return Gate::allows('force-delete-worklist', $worklist);
        }

        return Gate::allows('delete-worklist', $worklist);
    }

    public function prepareForValidation()
    {
        $this->merge(['force' => strtobool($this->input('force'))]);
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
