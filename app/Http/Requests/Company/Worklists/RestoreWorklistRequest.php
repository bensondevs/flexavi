<?php

namespace App\Http\Requests\Company\Worklists;

use App\Models\Worklist\Worklist;
use Illuminate\Foundation\Http\FormRequest;

class RestoreWorklistRequest extends FormRequest
{
    /**
     * Worklist model container
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
        $worklist = $this->getTrashedWorklist();

        return $this->user()
            ->fresh()
            ->can('restore-worklist', $worklist);
    }

    /**
     * Get Trashed Worklist based on supplied input
     *
     * @return Worklist
     */
    public function getTrashedWorklist()
    {
        if ($this->worklist) {
            return $this->worklist;
        }
        $id = $this->input('id') ?: $this->input('worklist_id');

        return $this->worklist = Worklist::onlyTrashed()->findOrFail($id);
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
