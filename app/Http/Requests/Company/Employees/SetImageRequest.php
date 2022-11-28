<?php

namespace App\Http\Requests\Company\Employees;

use App\Models\Employee\Employee;
use App\Rules\Helpers\Media;
use App\Traits\InputRequest;
use Illuminate\Foundation\Http\FormRequest;

class SetImageRequest extends FormRequest
{
    use InputRequest;

    /**
     * Employee model container
     *
     * @var Employee|null
     */
    private $employee;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()
            ->fresh()
            ->can('set-image-employee', $this->getEmployee());
    }

    /**
     * Get Employee based on supplied input
     *
     * @return Employee
     */
    public function getEmployee()
    {
        if ($this->employee) {
            return $this->employee;
        }
        $id = $this->input('id') ?: $this->input('employee_id');

        return $this->employee = Employee::withCount(
            'todayAppointments'
        )->findOrFail($id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'employee_image' => [
                'required',
                'image',
                'max:' . Media::MAX_IMAGE_SIZE,
                'mimes:' . Media::imageExtensions(),
            ],
        ]);

        return $this->returnRules();
    }
}
