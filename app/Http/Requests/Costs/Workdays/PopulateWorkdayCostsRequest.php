<?php

namespace App\Http\Requests\Costs\Workdays;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\CompanyPopulateRequestOptions;

use App\Models\Workday;

class PopulateWorkdayCostsRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    private $workday;

    public function getWorkday()
    {
        if ($this->workday) return $this->workday;

        $id = $this->input('id') ?: $this->input('workday_id');
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
        return Gate::allows('view-any-cost', $workday);
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
        $this->addWhereHas('workdays', [
            [
                'column' => 'workdays.id',
                'value' => $this->getWorkday()->id,
            ]
        ]);

        return $this->collectCompanyOptions();
    }
}
