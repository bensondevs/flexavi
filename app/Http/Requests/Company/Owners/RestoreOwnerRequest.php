<?php

namespace App\Http\Requests\Company\Owners;

use App\Models\Owner\Owner;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;

class RestoreOwnerRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Trashed Owner object
     *
     * @var Owner|null
     */
    private $trashedOwner;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $owner = $this->getTrashedOwner();

        return $this->checkCompanyPermission('restore owners', $owner);
    }

    /**
     * Get Trashed Owner based on supplied input
     *
     * @return Owner
     */
    public function getTrashedOwner()
    {
        return $this->trashedOwner =
            $this->trashedOwner ?:
                Owner::onlyTrashed()->findOrFail($this->input('id'));
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
