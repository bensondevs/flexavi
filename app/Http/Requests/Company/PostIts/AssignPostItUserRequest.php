<?php

namespace App\Http\Requests\Company\PostIts;

use App\Models\{PostIt\PostIt, User\User};
use Illuminate\Foundation\Http\FormRequest;

class AssignPostItUserRequest extends FormRequest
{
    /**
     * User  Model
     *
     * @var User|null
     */
    private $user;

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
        $assignedUser = $this->getAssignedUser();

        return $this->user()
            ->fresh()
            ->can('assign-user-post-it', [$postIt, $assignedUser]);
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

        return $this->postIt = PostIt::findOrFail($id);
    }

    /**
     * Get User based on supplied input
     *
     * @return User
     */
    public function getAssignedUser()
    {
        if ($this->user) {
            return $this->user;
        }

        $id = $this->input('assigned_user_id');
        return $this->user = User::findOrFail($id);
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
