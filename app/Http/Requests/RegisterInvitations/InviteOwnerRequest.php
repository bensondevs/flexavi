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
        return $this->getOwner();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'invited_email' => ['required', 'string', 'email'],
            'expiry_time' => ['datetime'],
        ]);

        return $this->returnRules();
    }

    public function invitationData()
    {
        $data = $this->onlyInRules();
        $data['attachments'] = [
            'model' => 'App\Models\Owner',
            'model_id' => $this->getOwner()->id,
            'related_column' => 'user_id',
            'role' => 'owner',
        ];

        return $data;
    }
}
