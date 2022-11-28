<?php

namespace App\Http\Requests\Company\ScheduleCars;

use App\Models\Car\Car;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;

class SaveScheduleCarRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Car object
     *
     * @var Car|null
     */
    private $car;

    /**
     * Get Car based on supplied input
     *
     * @return Car
     */
    public function getCar()
    {
        return $this->car =
            $this->car ?: Car::findOrFail($this->input('car_id'));
    }

    /**
     * Get ScheduleCar based on supplied input
     *
     * @return mixed
     */
    public function getSchedule()
    {
        // TODO: complete getSchedule logic
        // return $this->schedule =
        //     $this->schedule ?:
        //     ScheduleCar::findOrFail($this->input('schedule_id'));
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
        // $car = $this->getCar();
        // $schedule = $this->getSchedule();
        // $company = $this->getCompany();
        // $authorizeAction = $this->authorizeCompanyAction('schedule cars');
        // $authorizedCar = ($company->id == $car->company_id);
        // $authorizedSchedule = ($company->id == $schedule->company_id);
        // return $authorizeAction && ($authorizedCar && $authorizedSchedule);
        return false;
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

        return $this->returnRules();
    }
}
