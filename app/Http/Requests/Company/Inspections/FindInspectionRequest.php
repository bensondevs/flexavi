<?php

namespace App\Http\Requests\Company\Inspections;

use App\Models\Inspection\Inspection;
use App\Traits\RequestHasRelations;
use Illuminate\Foundation\Http\FormRequest;

class FindInspectionRequest extends FormRequest
{
    use RequestHasRelations;

    /**
     * List of loadable relationships
     *
     * @var array
     */
    private $relationNames = [
        'with_appointment' => false,
        'with_customer' => false,
        'with_company' => false,
        'with_pictures' => false
    ];

    /**
     * Found inspection model container
     *
     * @var Inspection|null
     */
    private $inspection;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $inspection = $this->getInspection();

        return $this->user()
            ->fresh()
            ->can('view-inspection', $inspection);
    }

    /**
     * Get Inspection based on supplied input
     *
     * @return Inspection
     */
    public function getInspection()
    {
        if ($this->inspection) {
            return $this->inspection;
        }
        $id = $this->input('id') ?: $this->input('inspection_id');
        $relations = $this->relations();

        return $this->inspection = Inspection::with($relations)->findOrFail($id);
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

    /**
     * Prepare input for validation.
     *
     * This will prepare input to configure the loadable relationships
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->prepareRelationInputs();
    }
}
