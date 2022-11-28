<?php

namespace App\Http\Requests\Company\ExecuteWorks;

use App\Enums\ExecuteWork\WarrantyTimeType;
use App\Enums\ExecuteWorkRelatedMaterial\RelatedMaterialStatus;
use App\Rules\Helpers\Media;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveExecuteWorkRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->fresh()->can('create-execute-work');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // related material sections
            'related_quotation' => ['required', Rule::in(RelatedMaterialStatus::getValues())],
            'related_invoice' => ['required', Rule::in(RelatedMaterialStatus::getValues())],
            'related_work_contract' => ['required', Rule::in(RelatedMaterialStatus::getValues())],
            'quotation_id' => ['nullable', Rule::exists('quotations', 'id')],
            'invoice_id' => ['nullable', Rule::exists('invoices', 'id')],
            'quotation_file' => [
                'nullable',
                'file',
                'max:' . Media::MAX_DOCUMENT_SIZE,
                'mimes:' . Media::documentExtensions()
            ],
            'invoice_file' => [
                'nullable',
                'file',
                'max:' . Media::MAX_DOCUMENT_SIZE,
                'mimes:' . Media::documentExtensions()
            ],
            'work_contract_file' => [
                'nullable',
                'file',
                'max:' . Media::MAX_DOCUMENT_SIZE,
                'mimes:' . Media::documentExtensions()
            ],


            'appointment_id' => ['required', 'string', Rule::exists('appointments', 'id')],
            'execute_work.*.length' => ['required', 'numeric'],
            'execute_work.*.note' => ['nullable', 'string'],
            'execute_work.*.pictures.*' => [
                'required',
                'image',
                'max:' . Media::MAX_IMAGE_SIZE,
                'mimes:' . Media::imageExtensions(),
            ],
            'execute_work.*.services.*.work_service_id' => ['required'],
            'execute_work.*.services.*.warranty_time_value' => ['nullable', 'numeric'],
            'execute_work.*.services.*.warranty_time_type' => ['nullable', Rule::in(WarrantyTimeType::getValues()), 'numeric'],
        ];
    }

    /**
     * Get the execute_work data
     *
     * @return array
     */
    public function executeWorkData()
    {
        $company = $this->getCompany();
        return [
            'company_id' => $company->id,
            'appointment_id' => $this->input('appointment_id')
        ];
    }

    /**
     * Get the execute work related material data
     *
     * @return array
     */
    public function executeWorkRelatedMaterialData()
    {
        return [
            'related_quotation' => $this->input('related_quotation') == 1 ? 1 : 0,
            'related_invoice' => $this->input('related_invoice') == 1 ? 1 : 0,
            'related_work_contract' => $this->input('related_work_contract') == 1 ? 1 : 0,
            'quotation_id' => $this->input('quotation_id'),
            'invoice_id' => $this->input('invoice_id'),
            'quotation_file' => $this->file('quotation_file'),
            'invoice_file' => $this->file('invoice_file'),
            'work_contract_file' => $this->file('work_contract_file'),
        ];
    }

    /**
     * Get the execute_work picture data
     *
     * @return array
     */
    public function executeWorkPhotosData()
    {
        return $this->except(['appointment_id'])['execute_work'];
    }
}
