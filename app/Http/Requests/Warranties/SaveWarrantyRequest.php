<?php

namespace App\Http\Requests\Warranties;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\CompanyInputRequest;

use App\Models\Warranty;

class SaveWarrantyRequest extends FormRequest
{
    use CompanyInputRequest;

    private $warranty;

    public function getWarranty()
    {
        return $this->warranty = $this->model = ($this->warranty) ?
            Warranty::findOrFail($this->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->authorizeCompanyAction(
            'warranties', 
            'workContract->company_id'
        );
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
