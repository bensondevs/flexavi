<?php

namespace App\Http\Requests\PostIts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\PostIt;

class DeletePostItRequest extends FormRequest
{
    private $postIt;

    public function getPostIt()
    {
        if ($this->postIt) return $this->postIt;

        $id = $this->input('post_it_id') ?: $this->input('id');
        return $this->postIt = PostIt::withTrashed()->findOrFail($id);
    }

    protected function prepareForValidation()
    {
        $force = $this->input('force');
        $this->merge(['force' => strtobool($force)]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $postIt = $this->getPostIt();
        $permissionName = ($this->input('force') ? 'force-' : '') . 'delete-post-it';
        return Gate::allows($permissionName, $postIt);
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
