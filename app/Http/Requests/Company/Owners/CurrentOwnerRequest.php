<?php

namespace App\Http\Requests\Company\Owners;

use App\Models\Owner\Owner;
use Illuminate\Foundation\Http\FormRequest;

class CurrentOwnerRequest extends FormRequest
{
    /**
     * Owner object
     *
     * @var Owner|null
     */
    private $owner;

    /**
     * Get Owner based on current user
     *
     * @return Owner
     */
    public function getOwner()
    {
        return $this->owner = $this->owner ?: $this->user()->owner;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()
            ->fresh()
            ->hasRole('owner');
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
