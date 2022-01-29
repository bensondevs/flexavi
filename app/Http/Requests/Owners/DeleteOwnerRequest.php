<?php

namespace App\Http\Requests\Owners;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Owner;

class DeleteOwnerRequest extends FormRequest
{
    /**
     * Owner deletion target
     * 
     * @var \App\Models\Owner|null
     */
    private $owner;

    /**
     * Get deleted owner
     * 
     * @return \App\Models\Owner|abort 404
     */
    public function getOwner()
    {
        if ($this->owner) return $this->owner;

        $id = $this->input('id') ?: $this->input('owner_id');
        return Owner::withTrashed()->findOrFail($id);
    }

    /**
     * Prepare input before validation
     * 
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->has('force')) {
            $force = strtobool($this->input('force'));
            $this->merge(['force' => $force]);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $owner = $this->getOwner();
        return Gate::allows('delete-owner', $owner);
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
