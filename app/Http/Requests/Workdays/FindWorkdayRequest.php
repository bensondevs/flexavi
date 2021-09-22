<?php

namespace App\Http\Requests\Workdays;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Workday;

use App\Traits\RequestHasRelations;

class FindWorkdayRequest extends FormRequest
{
    use RequestHasRelations;

    private $relationNames = [
        'with_worklists' => true,
        'with_appointments' => true,
        'with_costs' => false,
        'with_employees' => false,
    ];

    private $workday;

    public function getWorkday()
    {
        if ($this->workday) return $this->workday;

        $id = $this->input('workday_id') ?: $this->input('id');
        return $this->workday = Workday::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('view-workday', $this->getWorkday());
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
