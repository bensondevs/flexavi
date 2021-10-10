<?php

namespace App\Http\Requests\Costs\Worklists;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\CompanyPopulateRequestOptions;

use App\Models\Worklist;

class PopulateWorklistCostsRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    private $worklist;

    public function getWorklist()
    {
        if ($this->worklist) return $this->worklist;

        $id = $this->input('worklist_id');
        return $this->worklist = Worklist::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('view-any-cost');
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
        $this->addWhereHas('costables', [
            [
                'column' => 'costables.costable_id',
                'value' => $this->getWorklist()->id,
            ],
            [
                'column' => 'costables.costable_type',
                'value' => Worklist::class,
            ]
        ]);

        return $this->collectCompanyOptions();
    }
}
