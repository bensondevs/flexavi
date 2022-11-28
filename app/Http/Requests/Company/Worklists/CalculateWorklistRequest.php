<?php

namespace App\Http\Requests\Company\Worklists;

use App\Models\Worklist\Worklist;
use Illuminate\Foundation\Http\FormRequest;

class CalculateWorklistRequest extends FormRequest
{
    /**
     * Found worklist model container
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
            ->can('calculate-worklist', $worklist);
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
