<?php

namespace App\Http\Requests\Company\Permissions;

use App\Models\Employee\Employee;
use App\Models\Owner\Owner;
use App\Models\User\User;
use App\Traits\PopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class PopulatePermissionsRequest extends FormRequest
{
    use PopulateRequestOptions;

    /**
     * User object
     *
     * @var User|null
     */
    public $user;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()
            ->fresh()
            ->can('view-any-permission');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return User
     */
    public function getCurrentUser()
    {
        if ($this->user instanceof User)
            return $this->user;

        if ($id = $this->input('id'))
            $this->user = User::findOrFail($id);
        if ($id = $this->input('owner_id'))
            $this->user = Owner::findOrFail($id)->user;
        if ($id = $this->input('employee_id'))
            $this->user = Employee::findOrFail($id)->user;

        return $this->user;
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
