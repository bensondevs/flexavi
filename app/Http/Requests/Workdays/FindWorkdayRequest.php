<?php

namespace App\Http\Requests\Workdays;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use App\Traits\RequestHasRelations;

use App\Models\Workday;

class FindWorkdayRequest extends FormRequest
{
    use RequestHasRelations;

    /**
     * List of configurable relationships
     * 
     * @var array
     */
    protected $relationNames = [
        'with_company' => false,
        'with_worklists' => true,
        'with_appointments' => false,
        'with_costs' => false,
        'with_receipts' => false,
        'with_worklists_costs' => false,
        'with_employees' => false,
    ];

    /**
     * Found workday model container 
     * 
     * @var \App\Models\Workday|null
     */
    private $workday;

    /**
     * Get workday from supplied input of `workday_id` or `id`
     * 
     * @return \App\Models\Workday|abort 404
     */
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
