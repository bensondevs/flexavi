<?php

namespace App\Http\Requests\Company\Workdays\Worklists;

use App\Models\Workday\Workday;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;

class SaveWorkdayWorklistRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Workday object
     *
     * @var Workday|null
     */
    private $workday;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $workday = $this->getWorkday();

        return $this->user()
            ->fresh()
            ->can('create-worklist', $workday);
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'workday_id' => ['required', 'string'],
            'worklist_name' => ['required', 'string'],
        ]);

        return $this->returnRules();
    }
}
