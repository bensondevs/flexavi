<?php

namespace App\Http\Requests\Schedules;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Schedule;

class FindScheduleRequest extends FormRequest
{
    private $schedule;

    public function getSchedule()
    {
        return $this->schedule = ($this->schedule) ?:
            Schedule::findOrFail($this->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        $schedule = $this->getSchedule();

        $actionName = ($this->isMethod('GET')) ? 'view' : 'delete';
        $actionObject = 'schedules';
        $action = $actionName . ' ' . $actionObject;
        $authorizeAction = $user->hasCompanyPermission(
            $schedule->company_id, $action
        );
        
        return $authorizeAction;
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
