<?php

namespace App\Http\Requests\Company\Inspections;

use App\Rules\Helpers\Media;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInspectionRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->fresh()->can('create-inspection');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'appointment_id' => ['required', 'string', Rule::exists('appointments', 'id')],
            'inspection.*.length' => ['required', 'numeric'],
            'inspection.*.width' => ['required', 'numeric'],
            'inspection.*.amount' => ['required', 'numeric'],
            'inspection.*.note' => ['nullable', 'string'],
            'inspection.*.pictures.*' => [
                'required',
                'image',
                'max:' . Media::MAX_IMAGE_SIZE,
                'mimes:' . Media::imageExtensions(),
            ],
            'inspection.*.services.*' => ['required'],
        ];
    }

    /**
     * Get the Inspection data
     *
     * @return array
     */
    public function inspectionData()
    {
        $company = $this->getCompany();
        return [
            'company_id' => $company->id,
            'appointment_id' => $this->input('appointment_id')
        ];
    }

    /**
     * Get the Inspection picture data
     *
     * @return array
     */
    public function inspectionPictureData()
    {
        return $this->except(['appointment_id'])['inspection'];
    }
}
