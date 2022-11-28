<?php

namespace App\Http\Requests\Company\OwnerInvitations;

use App\Models\Owner\OwnerInvitation;
use App\Traits\InputRequest;
use App\Traits\RequestHasRelations;
use Illuminate\Foundation\Http\FormRequest;

class FindOwnerInvitationRequest extends FormRequest
{
    use InputRequest, RequestHasRelations;

    /**
     * List of loaded relation names
     *
     * @var array
     */
    protected array $relationNames = [
        'with_invited_user' => true,
    ];

    /**
     * OwnerInvitation object
     *
     * @var OwnerInvitation|null
     */
    private ?OwnerInvitation $ownerInvitation = null;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()
            ->fresh()
            ->can('view-invitation-owner', $this->getInvitation());
    }

    /**
     * Get OwnerInvitation based on supplied input
     *
     * @return OwnerInvitation|null
     */
    public function getInvitation(): ?OwnerInvitation
    {
        if ($this->ownerInvitation) {
            return $this->ownerInvitation;
        }
        $id = $this->input('id') ?: $this->input('employee_invitation_id');

        return $this->ownerInvitation = OwnerInvitation::findOrFail($id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Prepare inputted data according to expected form
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->prepareRelationInputs();
    }
}
