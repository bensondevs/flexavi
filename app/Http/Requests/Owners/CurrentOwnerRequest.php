<?php

namespace App\Http\Requests\Owners;

use Illuminate\Foundation\Http\FormRequest;

class CurrentOwnerRequest extends FormRequest
{
    private $owner;

    public function getOwner()
    {
        return $this->owner = ($this->owner) ?:
            $this->user()->owner; 
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasRole('owner');
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
