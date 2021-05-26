<?php

namespace App\Http\Requests\ScheduleCars;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\ScheduleCar;

class FindScheduleCarRequest extends FormRequest
{
    private $scheduleCar;

    public function getScheduleCar()
    {
        return $this->scheduleCar = $this->scheduleCar ?:
            ScheduleCar::findOrFail($this->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        $scheduleCar = $this->getScheduleCar();

        $actionName = ($this->isMethod('GET')) ? 'view' : 'delete';
        $actionObject = 'schedule cars';
        $action = $actionName . ' ' . $actionObject;
        $authorizedAction = $user->hasCompanyPermission(
            $scheduleCar->schedule->company_id, $action
        );

        return $authorizedAction;
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
