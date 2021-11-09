<?php

namespace App\Http\Requests\CarRegisterTimes;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\CompanyInputRequest;

use App\Models\Car;

class RegisterCarTimeRequest extends FormRequest
{
    use CompanyInputRequest;

    private $car;

    public function getCar()
    {
        if ($this->car) return $this->car;

        $id = $this->input('car_id');
        return $this->car = Car::findOrFail($id);
    }

    /**
     * Prepare received request values before rules.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'company_id' => $this->getCar()->company_id,
            'should_out_at' => (new \DateTime($this->input('should_out_at')))->format('c'),
            'should_return_at' => (new \DateTime($this->input('should_return_at')))->format('c'),
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $car = $this->getCar();
        return Gate::allows('register-car-time', $car);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'company_id' => ['required', 'string'],
            'should_out_at' => ['required', 'date'],
            'should_return_at' => ['required', 'date'],
        ]);

        return $this->returnRules();
    }
}
