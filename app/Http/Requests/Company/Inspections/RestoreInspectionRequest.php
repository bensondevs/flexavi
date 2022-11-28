<?php

namespace App\Http\Requests\Company\Inspections;

use App\Models\Inspection\Inspection;
use Illuminate\Foundation\Http\FormRequest;

class RestoreInspectionRequest extends FormRequest
{
    /**
     * Target inspection model container
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
            ->can('restore-inspection', $inspection);
    }

    /**
     * Get inspection based on supplied input
     *
     * @return Inspection
     */
    public function getInspection()
    {
        if ($this->inspection) {
            return $this->inspection;
        }
        $id = $this->input('inspection_id') ?: $this->input('id');

        return $this->inspection = Inspection::onlyTrashed()->findOrFail($id);
    }

    public function rules()
    {
        return [];
    }
}
