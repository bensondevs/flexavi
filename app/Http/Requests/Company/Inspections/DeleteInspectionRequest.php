<?php

namespace App\Http\Requests\Company\Inspections;

use App\Models\Inspection\Inspection;
use Illuminate\Foundation\Http\FormRequest;

class DeleteInspectionRequest extends FormRequest
{
    /**
     * Target Inspection model container
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
            ->can('delete-inspection', $inspection);
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

        return $this->inspection = Inspection::findOrFail($id);
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
     * Format input values before validations
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $force = strtobool($this->input('force'));
        $this->merge(['force' => $force]);
    }
}
