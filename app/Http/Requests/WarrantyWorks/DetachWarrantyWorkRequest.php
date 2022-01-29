<?php

namespace App\Http\Requests\WarrantyWorks;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use App\Models\WarrantyWork;

class DetachWarrantyWorkRequest extends FormRequest
{
    /**
     * Found warranty work
     * 
     * @var \App\Models\WarrantyWork|null
     */
    private $warrantyWork;

    /**
     * Get warranty work
     * 
     * @return \App\Models\WarrantyWork|abort 404
     */
    public function getWarrantyWork()
    {
        if ($this->warrantyWork) return $this->warrantyWork;

        $id = $this->input('warranty_work_id');
        return $this->warrantyWork = WarrantyWork::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $warrantyWork = $this->getWarrantyWork();
        return Gate::allows('detach-warranty-work', [$warrantyWork]);
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
