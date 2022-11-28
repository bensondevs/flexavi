<?php

namespace App\Http\Requests\Company\ScheduleCars;

use Illuminate\Foundation\Http\FormRequest;

class FindScheduleCarRequest extends FormRequest
{
    /**
     * Get ScheduleCar based on supplied input
     *
     * @return mixed
     */
    public function getScheduleCar()
    {
        // TODO: complete getScheduleCar logic
        // return $this->scheduleCar =
        //     $this->scheduleCar ?: ScheduleCar::findOrFail($this->input('id'));
        return null;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // TODO: complete authorize logic
        // $user = $this->user();
        // $scheduleCar = $this->getScheduleCar();
        // $schedule = $scheduleCar->schedule;
        // $actionName = $this->isMethod('GET') ? 'view' : 'delete';
        // $actionObject = 'schedule cars';
        // $action = $actionName . ' ' . $actionObject;
        // $authorizedAction = $user->hasCompanyPermission(
        //     $schedule->company_id,
        //     $action
        // );
        // return $authorizedAction;
        return false;
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
