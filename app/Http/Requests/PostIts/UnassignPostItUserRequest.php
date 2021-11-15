<?php

namespace App\Http\Requests\PostIts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\{ User, PostItAssignedUser };

class UnassignPostItUserRequest extends FormRequest
{
    private $pivot;

    public function getAssignedUserPivot()
    {
        if ($this->pivot) return $this->pivot;

        if ($id = $this->input('post_it_assigned_user_id')) {
            return $this->pivot = PostItAssignedUser::findOrFail($id);
        }

        return $this->pivot = PostItAssignedUser::where('user_id', $this->input('user_id'))
            ->where('post_it_id', $this->input('post_it_id'))
            ->firstOrFail();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $pivot = $this->getAssignedUserPivot();
        return Gate::allows('unassign-user-post-it', $pivot);
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
