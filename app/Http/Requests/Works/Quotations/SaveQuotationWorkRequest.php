<?php

namespace App\Http\Requests\Works\Quotations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Http\Requests\Works\SaveWorkRequest;

use App\Traits\InputRequest;

use App\Models\Quotation;

class SaveQuotationWorkRequest extends FormRequest
{
    use InputRequest;

    /**
     * Found quotation model container
     * 
     * @var \App\Models\Appointment|null
     */
    private $quotation;

    /**
     * Find Appointment or abort 404
     * 
     * @return \App\Models\Appointment
     */
    public function getQuotation()
    {
        if ($this->quotation) return $this->quotation;

        $id = $this->input('id');
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
        return Gate::allows('create-work');
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
