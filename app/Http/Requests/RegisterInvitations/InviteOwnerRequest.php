<?php

namespace App\Http\Requests\RegisterInvitations;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\CompanyInputRequest;

use App\Models\Owner;

class InviteOwnerRequest extends FormRequest
{
    use CompanyInputRequest;

    private $owner;

    public function getOwner()
    {
        return $this->owner = $this->owner ?:
            Owner::findOrFail($this->input('owner_id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        $owner = $this->getOwner();

        return $this->authorizeCompanyAction(
            $owner->company_id,
            'send register invitations'
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'invited_email' => ['required', 'string'],
            'expiry_time' => ['required', 'datetime'],
        ];
    }

    public function invitationData()
    {
        $data = $this->onlyInRules();
        $data['attachments'] = [
            'model' => 'App\Models\Owner',
            'model_id' => $this->getOwner()->id,
            'related_column' => 'user_id',
        ];

        return $data;
    }
}
