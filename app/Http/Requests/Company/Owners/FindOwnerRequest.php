<?php

namespace App\Http\Requests\Company\Owners;

use App\Models\Owner\Owner;
use App\Traits\RequestHasRelations;
use Illuminate\Foundation\Http\FormRequest;

class FindOwnerRequest extends FormRequest
{
    use RequestHasRelations;

    /**
     * List of relationships that will be loaded
     * Set the attribute to true, it will load the relationship
     * upon the response
     *
     * @var array
     */
    protected $relationNames = [
        'with_company' => false,
        'with_address' => true,
        'with_user' => false,
        'with_user.permissions' => false,
    ];

    /**
     * Owner model container variable
     *
     * @var Owner|null
     */
    private $owner;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $owner = $this->getOwner();

        return $this->user()
            ->fresh()
            ->can('view-owner', $owner);
    }

    /**
     * Get Owner based on supplied input
     *
     * @return Owner
     */
    public function getOwner(): ?Owner
    {
        if ($this->owner) {
            return $this->owner;
        }
        $relations = $this->relations();
        if ($id = $this->input('id') ?: $this->input('owner_id')) {
            return $this->owner = Owner::with($relations)->findOrFail($id);
        }
        $user = $this->user()->fresh();

        return $this->owner = Owner::with($relations)
            ->where('user_id', $user->id)
            ->firstOrFail();
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

    /**
     * Prepare input to load the relationships
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->prepareRelationInputs();
    }
}
