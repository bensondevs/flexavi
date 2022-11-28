<?php

namespace App\Http\Requests\Company\OwnerInvitations;

use App\Models\Permission\Role;
use App\Enums\Role as RoleType;
use App\Traits\PopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PopulateOwnerPermissionsRequest extends FormRequest
{
    use PopulateRequestOptions;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get options of request
     *
     * @return array
     */
    public function options(): array
    {
        $roleType = $this->input('role');
        $role = Role::where('name', $roleType)->first();

        $this->addWith('modulePermissions');

        $this->addWhere([
            'column' => 'role_id',
            'value' => $role->id
        ]);

        return $this->collectOptions();
    }

     /**
     * Prepare input request before validation
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'role' => $this->get('role', RoleType::Employee)
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'role' => ['required', 'string', Rule::in(RoleType::getValues())],
        ];
    }
}
