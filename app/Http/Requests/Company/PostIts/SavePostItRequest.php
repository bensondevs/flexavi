<?php

namespace App\Http\Requests\Company\PostIts;

use App\Models\PostIt\PostIt;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;

class SavePostItRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Found post it to be updated
     *
     * @var PostIt|null
     */
    private $postIt;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user()->fresh();
        if (!$this->isMethod('POST')) {
            $postIt = $this->getPostIt();
            return $user->can('edit-post-it', $postIt);
        }

        return $user->can('create-post-it');
    }

    /**
     * Get PostIt based on supplied input
     *
     * @return PostIt
     */
    public function getPostIt()
    {
        if ($this->postIt) {
            return $this->postIt;
        }
        $id = $this->input('post_it_id');

        return $this->postIt = PostIt::findOrFail($id);
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

    /**
     * Prepare for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $company = $this->user()->company;
        $this->merge(['company_id' => $company->id]);
    }
}
