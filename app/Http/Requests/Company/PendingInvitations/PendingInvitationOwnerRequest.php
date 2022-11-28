<?php

namespace App\Http\Requests\Company\PendingInvitations;

use App\Enums\RegisterInvitation\RegisterInvitationStatus;
use App\Traits\PopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class PendingInvitationOwnerRequest extends FormRequest
{
    use PopulateRequestOptions;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()
            ->fresh()
            ->can('view-pending-invitation-owner');
    }

    /**
     * Get options
     *
     * @return array
     */
    public function options()
    {
        $this->setWheres([
            [
                'column' => 'role',
                'operator' => '=',
                'value' => 'owner',
            ],
            [
                'column' => 'status',
                'operator' => '=',
                'value' => RegisterInvitationStatus::Active,
            ],
        ]);

        return $this->collectOptions();
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
