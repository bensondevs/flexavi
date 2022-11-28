<?php

namespace App\Http\Requests\Company\Schedules;

use App\Models\Schedule\Schedule;
use Illuminate\Foundation\Http\FormRequest;

class FindScheduleRequest extends FormRequest
{
    /**
     * Schedule object
     *
     * @var Schedule|null
     */
    private $schedule;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user()->fresh();
        $schedule = $this->getSchedule();
        $actionName = $this->isMethod('GET') ? 'view' : 'delete';
        $actionObject = 'schedules';
        $action = $actionName . ' ' . $actionObject;
        $authorizeAction = $user->hasCompanyPermission(
            $schedule->company_id,
            $action
        );

        return $authorizeAction;
    }

    /**
     * Get Schedule based on supplied input
     *
     * @return Schedule
     */
    public function getSchedule()
    {
        return $this->schedule =
            $this->schedule ?: Schedule::findOrFail($this->input('id'));
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
