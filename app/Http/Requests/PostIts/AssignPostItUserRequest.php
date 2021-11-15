<?php

namespace App\Http\Requests\PostIts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\{ User, PostIt };

class AssignPostItUserRequest extends FormRequest
{
    private $user;
    private $postIt;

    public function getAssignedUser()
    {
        if ($this->user) return $this->user;

        $id = $this->input('assigned_user_id');
        return $this->user = User::findOrFail($id);
    }

    public function getPostIt()
    {
        if ($this->postIt) return $this->postIt;

        $id = $this->input('post_it_id') ?: $this->input('id');
        return $this->postIt = PostIt::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $postIt = $this->getPostIt();
        $assignedUser = $this->getAssignedUser();
        return Gate::allows('assign-user-post-it', [$postIt, $assignedUser]);
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
