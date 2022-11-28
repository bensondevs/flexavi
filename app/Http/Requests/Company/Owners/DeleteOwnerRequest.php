<?php

namespace App\Http\Requests\Company\Owners;

use App\Models\Owner\Owner;
use Illuminate\Foundation\Http\FormRequest;

class DeleteOwnerRequest extends FormRequest
{
    /**
     * Owner deletion target
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
            ->can('delete-owner', $owner);
    }

    /**
     * Get Owner based on supplied input
     *
     * @return Owner
     */
    public function getOwner()
    {
        if ($this->owner) {
            return $this->owner;
        }
        $id = $this->input('id') ?: $this->input('owner_id');

        return $this->owner = Owner::withTrashed()->findOrFail($id);
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
}
