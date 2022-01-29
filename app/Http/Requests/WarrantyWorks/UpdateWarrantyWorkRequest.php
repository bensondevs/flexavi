<?php

namespace App\Http\Requests\WarrantyWorks;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use App\Models\warrantyWork;

class UpdateWarrantyWorkRequest extends FormRequest
{
    /**
     * Target warranty work of the update
     * 
     * @var \App\Models\WarrantyWork
     */
    private $warrantyWork;

    /**
     * Get warranty work
     * 
     * @return \App\Models\Warranty|null
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
        return Gate::allows('update-warranty-work', [$warrantyWork]);
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
