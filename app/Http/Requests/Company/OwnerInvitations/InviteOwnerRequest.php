<?php

namespace App\Http\Requests\Company\OwnerInvitations;

use App\Rules\NotDuplicateOwnerInvitationRule;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class InviteOwnerRequest extends FormRequest
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
            ->can('send-invitation-owner');
    }

    /**
     * Handle permissions array input.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        if (!is_array($this->input('permission_names'))) {
            $this->merge([
                'permission_names' => json_decode($this->input('permission_names'), true)
            ]);
        }
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
                new NotDuplicateOwnerInvitationRule()
            ],
            'expiry_time' => ['nullable','date', 'after:today'],
            'name' => ['required', 'string'],
            'phone' => ['required'],
            'permission_names' => ['nullable', 'array'],
            'permission_names.*' => ['nullable', 'exists:permissions,name'],
        ];
    }

    /**
     * Get invitation data
     *
     * @return array
     */
    public function invitationData(): array
    {
        return array_merge(
            Arr::only($this->validated(), [
                'invited_email',
                'expiry_time',
                'name',
                'phone'
            ]),
            [
                'company_id' => $this->getCompany()->id,
                'permissions' =>  $this->input('permission_names') ,
            ]
        );
    }
}
