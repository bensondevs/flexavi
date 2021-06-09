<?php

namespace App\Http\Requests\Owners;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Owner;

use App\Traits\CompanyInputRequest;

class RestoreOwnerRequest extends FormRequest
{
    use CompanyInputRequest;

    private $trashedOwner;

    public function getDeletedOwner()
    {
        return $this->trashedOwner = $this->trashedOwner ?:
            Owner::withTrashed()->findOrFail($this->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $owner = $this->getDeletedOwner();
        return $this->checkCompanyPermission('restore owners', $owner);
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