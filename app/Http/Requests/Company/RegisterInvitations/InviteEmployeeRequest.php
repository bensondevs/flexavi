<?php

namespace App\Http\Requests\Company\RegisterInvitations;

use App\Enums\Employee\EmployeeType;
use App\Rules\Helpers\Media;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InviteEmployeeRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()
            ->fresh()
            ->can('send-employee-register-invitation');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'invited_email' => [
                'required',
                'string',
                'email',
                'unique:users,email',
            ],
            'expiry_time' => ['date'],
            'name' => ['required', 'string'],
            'birth_date' => ['required', 'date'],
            'phone' => ['required', 'unique:users,phone'],
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
        ]);

        return $this->returnRules();
    }

    /**
     * Get the invitation data
     *
     * @return array
     */
    public function invitationData()
    {
        $input = $this->all();
        $input['attachments'] = $this->attachments();

        return $input;
    }

    /**
     * Get the attachments data
     *
     * @return array
     */
    public function attachments()
    {
        $attachments = $this->except([
            'invited_email',
            'expiry_time',
            'contract_file',
            'phone',
            'name',
            'birth_date',
            'role',
        ]);
        $attachments['title'] = $this->input('name');
        $attachments['company_id'] = $this->getCompany()->id;

        return $attachments;
    }
}
