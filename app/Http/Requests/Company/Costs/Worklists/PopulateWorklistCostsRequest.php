<?php

namespace App\Http\Requests\Company\Costs\Worklists;

use App\Models\Worklist\Worklist;
use App\Traits\CompanyPopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class PopulateWorklistCostsRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    /**
     * Worklist object
     *
     * @var Worklist|null
     */
    private $worklist;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()
            ->fresh()
            ->can('view-any-cost');
    }

    /**
     * Get options
     *
     * @return array
     */
    public function options()
    {
        $this->addWhereHas('costables', [
            [
                'column' => 'costables.costable_id',
                'value' => $this->getWorklist()->id,
            ],
            [
                'column' => 'costables.costable_type',
                'value' => Worklist::class,
            ],
        ]);

        return $this->collectCompanyOptions();
    }

    /**
     * Get Worklist based on supplied input
     *
     * @return Worklist
     */
    public function getWorklist()
    {
        if ($this->worklist) {
            return $this->worklist;
        }
        $id = $this->input('worklist_id');

        return $this->worklist = Worklist::findOrFail($id);
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
