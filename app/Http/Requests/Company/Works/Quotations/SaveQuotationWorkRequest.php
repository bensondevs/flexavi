<?php

namespace App\Http\Requests\Company\Works\Quotations;

use App\Http\Requests\Company\Works\SaveWorkRequest;
use App\Models\Quotation\Quotation;
use App\Traits\InputRequest;
use Illuminate\Foundation\Http\FormRequest;

class SaveQuotationWorkRequest extends FormRequest
{
    use InputRequest;

    /**
     * Found quotation model container
     *
     * @var Quotation|null
     */
    private $quotation;

    /**
     * Get Quotation based on supplied input
     *
     * @return Quotation
     */
    public function getQuotation()
    {
        if ($this->quotation) {
            return $this->quotation;
        }
        $id = $this->input('quotation_id') ?? $this->input('id');

        return $this->quotation = Quotation::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()
            ->fresh()
            ->can('create-work');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $saveRequest = new SaveWorkRequest();
        $rules = $saveRequest->rules();

        return $rules;
    }
}
