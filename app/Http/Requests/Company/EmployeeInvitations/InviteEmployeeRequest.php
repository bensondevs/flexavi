<?php

namespace App\Http\Requests\Company\EmployeeInvitations;

use App\Enums\Employee\EmployeeType;
use App\Rules\Helpers\Media;
use App\Rules\NotDuplicateEmployeeInvitationRule;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class InviteEmployeeRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()
            ->fresh()
            ->can('send-invitation-employee');
    }

    /**
     * Prepare input before validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        if (is_json_string($this->input('permissions'))) {
            $permissions = $this->input('permissions');
            $this->merge([
                'permissions' => json_decode($permissions, true),
            ]);
        }


        $this->merge([
            'expiry_time' => $this->get(
                'expiry_time',
                now()->copy()->addDays(7)->toDateString()
            ),
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
            'invited_email' => [
                'required',
                'string',
                'email',
                'unique:users,email',
                new NotDuplicateEmployeeInvitationRule(),
            ],

            'title' => ['required', 'string'],
            'expiry_time' => ['required' ,'date'],
            'name' => ['required', 'string'],
            'birth_date' => ['required','date'],
            'phone' => ['required'],

            'role' => [
                'required',
                'numeric',
                Rule::in(EmployeeType::getValues()),
            ],
            'contract_file' => [
                'required',
                'file',
                'max:' . Media::MAX_DOCUMENT_SIZE,
                'mimes:' . Media::documentExtensions(),
            ],

            'permissions' => ['required', 'array'],
            'permissions.*' => ['required', 'string', 'exists:permissions,name'],
        ];
    }

    /**
     * Get invitation data
     *
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function invitationData(): array
    {
        $input = $this->validated();
        unset($input['confirm_password']);

        $input['permissions'] = $this->permissions();
        $input['company_id'] = $this->getCompany()->id;

        return $input;
    }

    /**
     * Get permissions attached to the request.
     *
     * @return array
     */
    public function permissions(): array
    {
        return $this->input('permissions');
    }
}
