<?php

namespace App\Http\Requests\PostIts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\CompanyInputRequest;

use App\Models\PostIt;

class SavePostItRequest extends FormRequest
{
    use CompanyInputRequest;

    private $postIt;

    public function getPostIt()
    {
        if ($this->postIt) return $this->postIt;

        $id = $this->input('post_it_id');
        return $this->postIt = PostIt::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (! $this->isMethod('POST')) {
            $postIt = $this->getPostIt();
            return Gate::allows('edit-post-it', $postIt);
        }

        return Gate::allows('create-post-it');
    }

    protected function prepareForValidation()
    {
        $company = $this->user()->company;
        $this->merge(['company_id' => $company->id]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'company_id' => ['required', 'string'],
            'content' => ['required', 'string'],
        ]);

        return $this->returnRules();
    }
}
