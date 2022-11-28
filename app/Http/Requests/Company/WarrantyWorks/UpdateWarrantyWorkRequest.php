<?php

namespace App\Http\Requests\Company\WarrantyWorks;

use App\Models\Warranty\warrantyWork;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWarrantyWorkRequest extends FormRequest
{
    /**
     * Target warranty work of the update
     *
     * @var WarrantyWork|null
     */
    private $warrantyWork;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $warrantyWork = $this->getWarrantyWork();

        return $this->user()
            ->fresh()
            ->can('update-warranty-work', [$warrantyWork]);
    }

    /**
     * Get WarrantyWork based on supplied input
     *
     * @return WarrantyWork
     */
    public function getWarrantyWork()
    {
        if ($this->warrantyWork) {
            return $this->warrantyWork;
        }
        $id = $this->input('warranty_work_id');

        return $this->warrantyWork = WarrantyWork::findOrFail($id);
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
