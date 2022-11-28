<?php

namespace App\Http\Requests\Company\Warranties;

use App\Models\Warranty\Warranty;
use Illuminate\Foundation\Http\FormRequest;

class DeleteWarrantyRequest extends FormRequest
{
    /**
     * Target warranty class container
     *
     * @var Warranty|null
     */
    private $warranty;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $warranty = $this->getWarranty();
        $user = $this->user()->fresh();

        return $this->input('force', false)
            ? $user->can('force-delete-warranty', $warranty)
            : $user->can('delete-warranty', $warranty);
    }

    /**
     * Get Warranty based on supplied input
     *
     * @return Warranty
     */
    public function getWarranty()
    {
        if ($this->warranty) {
            return $this->warranty;
        }
        $id = $this->input('id') ?: $this->input('warranty_id');

        return $this->warranty = Warranty::findOrFail($id);
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
        $this->merge(['force' => $this->input('force', false)]);
    }
}
