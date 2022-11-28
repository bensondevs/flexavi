<?php

namespace App\Http\Requests\Company\Workdays\Worklists;

use App\Models\Workday\Workday;
use App\Models\Worklist\Worklist;
use Illuminate\Foundation\Http\FormRequest;

class AttachWorklistRequest extends FormRequest
{
    /**
     * Workday object
     *
     * @var Workday|null
     */
    private $workday;

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
        $workday = $this->getWorkday();
        $worklist = $this->getWorklist();

        return $this->user()
            ->fresh()
            ->can('attach-worklist-workday', [$worklist, $workday]);
    }

    /**
     * Get Workday based on supplied input
     *
     * @return Workday
     */
    public function getWorkday()
    {
        if ($this->workday) {
            return $this->workday;
        }
        $id = $this->input('workday_id');

        return $this->workday = Workday::findOrFail($id);
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
        $id = $this->input('worklist_id');

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
