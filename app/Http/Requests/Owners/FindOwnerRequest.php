<?php

namespace App\Http\Requests\Owners;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use App\Traits\RequestHasRelations;

use App\Models\Owner;

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
        'with_company' => true,
        'with_addresses' => true,
        'with_user' => false,
    ];

    /**
     * Prepare input to load the relationships
     * 
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->prepareRelationInputs();
    }

    /**
     * Owner model container variable
     * 
     * @var \App\Models\Owner
     */
    private $owner;

    /**
     * Get owner and it's loaded relationships
     * 
     * @return \App\Models\Owner|abort 404
     */
    public function getOwner()
    {
        if ($this->owner) return $this->owner;

        $relations = $this->relations();

        if ($id = $this->input('id') ?: $this->input('owner_id')) {
            return $this->owner = Owner::with($relations)->findOrFail($id);
        }

        $user = $this->user();
        return $this->owner = Owner::with($relations)
            ->where('user_id', $user->id)
            ->firstOrFail();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $owner = $this->getOwner();
        return Gate::allows('view-owner', $owner);
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
