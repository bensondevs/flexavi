<?php

namespace App\Http\Requests\Company\FrequentlyAskedQuestions;

use App\Models\FAQ\FrequentlyAskedQuestion;
use Illuminate\Foundation\Http\FormRequest;

class FindRequest extends FormRequest
{
    /**
     * Found target faq container
     *
     * @var FrequentlyAskedQuestion|null
     */
    private $faq;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()
            ->fresh()
            ->can('view-faq');
    }

    /**
     * Get FrequentlyAskedQuestion based on supplied input
     *
     * @return FrequentlyAskedQuestion
     */
    public function getFaq()
    {
        if ($this->faq) {
            return $this->faq;
        }
        $id = $this->input('id') ?: $this->input('faq_id');

        return $this->faq = FrequentlyAskedQuestion::findOrFail($id);
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
