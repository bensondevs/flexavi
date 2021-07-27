<?php

namespace App\Http\Requests\Quotations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\InputRequest;

use App\Rules\Base64File;

use App\Models\Quotation;

class AddQuotationAttachmentRequest extends FormRequest
{
    use InputRequest;

    private $quotation;

    public function getQuotation()
    {
        if ($this->quotation) return $this->quotation;

        $id = $this->input('quotation_id');
        return $this->quotation = Quotation::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $quotation = $this->getQuotation();
        return Gate::allows('add-quotation-attachment', $quotation);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'name' => ['required', 'string'],
            'description' => ['string'],
            'attachment' => ['required', 'file', 'mimes:pdf,doc,docx,png,jpg,png,jpeg,svg', 'max:5000'],
        ]);

        if (is_base64_string($this->attachment)) {
            $this->rules['attachment'] = ['required', new Base64File('pdf,doc,docx,png,jpg,png,jpeg,svg')];
        }

        return $this->returnRules();
    }
}
