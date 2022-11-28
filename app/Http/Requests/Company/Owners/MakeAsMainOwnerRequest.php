<?php

namespace App\Http\Requests\Company\Owners;

use App\Models\Owner\Owner;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class MakeAsMainOwnerRequest extends FormRequest
{
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
        return $this->user()->fresh()->can('edit-owner', $this->getOwner());
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

        $id = $this->get('id') ?? $this->input('owner_id');

        return $this->owner = Owner::findOrFail($id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => ['required', 'string']
        ];
    }

    /**
     * Prepare input for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        //
    }
}
