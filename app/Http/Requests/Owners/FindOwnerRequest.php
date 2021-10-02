<?php

namespace App\Http\Requests\Owners;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Owner;

use App\Traits\RequestHasRelations;

class FindOwnerRequest extends FormRequest
{
    use RequestHasRelations;

    protected $relationNames = [
        'with_company' => true,
        'with_addresses' => true,
        'with_user' => false,
    ];

    private $owner;

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

    protected function prepareForValidation()
    {
        $this->prepareRelationInputs();
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
