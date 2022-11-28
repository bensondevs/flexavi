<?php

namespace App\Http\Requests\Company\FrequentlyAskedQuestions;

use App\Traits\PopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class PopulateRequest extends FormRequest
{
    use PopulateRequestOptions;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()
            ->fresh()
            ->can('view-any-faq');
    }

    /**
     * Get options
     *
     * @return array
     */
    public function options()
    {
        if ($this->has('search')) {
            $this->setSearch(request('search'));
        }

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
