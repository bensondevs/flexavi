<?php

namespace App\Http\Requests\Company\PostIts;

use App\Models\PostIt\PostItAssignedUser;
use Illuminate\Foundation\Http\FormRequest;

class UnassignPostItUserRequest extends FormRequest
{
    /**
     * Assigned User  Model
     *
     * @var PostItAssignedUser|null
     */
    private $pivot;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $pivot = $this->getAssignedUserPivot();

        return $this->user()
            ->fresh()
            ->can('unassign-user-post-it', $pivot);
    }

    /**
     * Get PostItAssignedUser based on supplied input
     *
     * @return PostItAssignedUser
     */
    public function getAssignedUserPivot()
    {
        if ($this->pivot) {
            return $this->pivot;
        }
        if ($id = $this->input('post_it_assigned_user_id')) {
            return $this->pivot = PostItAssignedUser::findOrFail($id);
        }

        return $this->pivot = PostItAssignedUser::where(
            'user_id',
            $this->input('user_id')
        )
            ->where('post_it_id', $this->input('post_it_id'))
            ->firstOrFail();
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
