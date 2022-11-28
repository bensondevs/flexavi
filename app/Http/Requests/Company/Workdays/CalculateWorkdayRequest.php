<?php

namespace App\Http\Requests\Company\Workdays;

use App\Models\Workday\Workday;
use Illuminate\Foundation\Http\FormRequest;

class CalculateWorkdayRequest extends FormRequest
{
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
        return $this->user()
            ->fresh()
            ->can('calculate-workday', $this->getWorkday());
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
        $id = $this->input('workday_id') ?: $this->input('id');

        return $this->workday = Workday::findOrFail($id);
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
