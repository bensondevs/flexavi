<?php

namespace App\Http\Requests\Admin\Pricings;

use App\Traits\PopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class PopulatePricingsRequest extends FormRequest
{
    use PopulateRequestOptions;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function options()
    {
        return $this->collectOptions();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
