<?php

namespace App\Http\Requests\Company\Costs\Workdays;

use App\Models\Workday\Workday;
use App\Traits\CompanyPopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class PopulateWorkdayCostsRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

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
        $workday = $this->getWorkday();

        return $this->user()
            ->fresh()
            ->can('view-any-cost', $workday);
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
        $id = $this->input('id') ?: $this->input('workday_id');

        return $this->workday = Workday::findOrFail($id);
    }

    /**
     * Get options
     *
     * @return array
     */
    public function options()
    {
        $this->addWhereHas('workdays', [
            [
                'column' => 'workdays.id',
                'value' => $this->getWorkday()->id,
            ],
        ]);

        return $this->collectCompanyOptions();
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
