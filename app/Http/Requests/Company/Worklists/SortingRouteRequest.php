<?php

namespace App\Http\Requests\Company\Worklists;

use App\Enums\Worklist\WorklistSortingRouteStatus;
use App\Models\Worklist\Worklist;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SortingRouteRequest extends FormRequest
{
    /**
     * Found worklist model container
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
        $worklist = $this->getWorklist();

        return $this->user()
            ->fresh()
            ->can('sorting-route-worklist', $worklist);
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
        $id = $this->input('worklist_id') ?: $this->input('id');

        return $this->worklist = Worklist::findOrFail($id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sorting_route_status' => [
                'required',
                Rule::in(WorklistSortingRouteStatus::getValues()),
            ],
            'always_sorting_route_status' => [
                'required',
                Rule::in(WorklistSortingRouteStatus::getValues()),
            ],
        ];
    }
}
