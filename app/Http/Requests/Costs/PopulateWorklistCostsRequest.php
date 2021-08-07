<?php

namespace App\Http\Requests\Costs;

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
        $worklist = $this->getWorklist();
        return Gate::allows('view-any-worklist-cost', $worklist);
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
        $this->addWhereHas('worklists', [
            [
                'column' => 'worklists.id',
                'value' => $this->getWorklist()->id,
            ]
        ]);

        return $this->collectCompanyOptions();
    }
}
