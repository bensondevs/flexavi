<?php

namespace App\Http\Requests\Quotations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\QuotationAttachment;

class RemoveQuotationAttachmentRequest extends FormRequest
{
    private $attachment;

    public function getQuotationAttachment()
    {
        if ($this->attachment) return $this->attachment;

        $id = $this->input('id');
        return $this->attachment = QuotationAttachment::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $attachment = $this->getQuotationAttachment();
        $quotation = $attachment->quotation;

        return Gate::allows('remove-quotation-attachment', $quotation);
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
}
