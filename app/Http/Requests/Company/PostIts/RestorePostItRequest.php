<?php

namespace App\Http\Requests\Company\PostIts;

use App\Models\PostIt\PostIt;
use Illuminate\Foundation\Http\FormRequest;

class RestorePostItRequest extends FormRequest
{
    /**
     * Post It Model
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
        $postIt = $this->getPostIt();

        return $this->user()
            ->fresh()
            ->can($postIt, 'restore-post-it');
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
        $id = $this->input('post_it_id') ?: $this->input('id');

        return $this->postIt = PostIt::onlyTrashed()->findOrFail($id);
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
