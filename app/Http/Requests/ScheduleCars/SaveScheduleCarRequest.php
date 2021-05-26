<?php

namespace App\Http\Requests\ScheduleCars;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\CompanyInputRequest;

use App\Models\Car;
use App\Models\Schedule;

class SaveScheduleCarRequest extends FormRequest
{
    use CompanyInputRequest;

    private $car;
    private $schedule;

    public function getCar()
    {
        return $this->car = ($this->car) ?:
            Car::findOrFail($this->input('car_id'));
    }

    public function getSchedule()
    {
        return $this->schedule = ($this->schedule) ?:
            ScheduleCar::findOrFail($this->input('schedule_id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $car = $this->getCar();
        $schedule = $this->getSchedule();
        $company = $this->getCompany();

        $authorizeAction = $this->authorizeCompanyAction('schedule cars');
        $authorizedCar = ($company->id == $car->company_id);
        $authorizedSchedule = ($company->id == $schedule->company_id);

        return $authorizeAction && ($authorizedCar && $authorizedSchedule);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'schedule_id' => ['required'],
            'car_id' => ['required'],
        ]);
    }
}
