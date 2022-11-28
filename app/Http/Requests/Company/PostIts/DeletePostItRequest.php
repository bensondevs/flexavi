<?php

namespace App\Http\Requests\Company\PostIts;

use App\Models\PostIt\PostIt;
use Illuminate\Foundation\Http\FormRequest;

class DeletePostItRequest extends FormRequest
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
        $permissionName =
            ($this->input('force') ? 'force-' : '') . 'delete-post-it';

        return $this->user()
            ->fresh()
            ->can($permissionName, $postIt);
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

        return $this->postIt = PostIt::withTrashed()->findOrFail($id);
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

    /**
     * Prepare for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $force = $this->input('force');
        $this->merge(['force' => strtobool($force)]);
    }
}
