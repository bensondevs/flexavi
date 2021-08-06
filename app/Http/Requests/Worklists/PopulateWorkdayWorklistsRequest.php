<?php

namespace App\Http\Requests\Worklists;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\CompanyPopulateRequestOptions;

use App\Models\Workday;

class PopulateWorkdayWorklistsRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    private $workday;

    public function getWorkday()
    {
        if ($this->workday) return $this->workday;

        $id = $this->input('workday_id');
        return $this->workday = Workday::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $workday = $this->getWorkday();
        return Gate::allows('view-any-worklist-workday', $workday);
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

    public function options()
    {
        $start = $this->input('start') ?: month_start_date();
        $end = $this->input('end') ?: current_date();

        $this->addWhereHas('workday', [
            [
                'column' => 'date',
                'operator' => '>=',
                'value' => month_start_date(),
            ],
            [
                'column' => 'date',
                'operator' => '<=',
                'value' => $end,
            ]
        ]);

        return $this->collectCompanyOptions();
    }
}
