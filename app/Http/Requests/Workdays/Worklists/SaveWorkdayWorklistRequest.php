<?php

namespace App\Http\Requests\Workdays\Worklists;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\CompanyInputRequest;

use App\Models\{Workday, Worklist};

class SaveWorkdayWorklistRequest extends FormRequest
{
    use CompanyInputRequest;

    private $workday;

    public function getWorkday()
    {
        if ($this->workday) return $this->workday;

        $id = $this->input('workday_id');
        return $this->workday = Workday::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'workday_id' => ['required', 'string'],
            'worklist_name' => ['required', 'string'],
        ]);

        return $this->returnRules();
    }
}
