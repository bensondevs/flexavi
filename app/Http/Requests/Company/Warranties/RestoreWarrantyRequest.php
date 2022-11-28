<?php

namespace App\Http\Requests\Company\Warranties;

use App\Models\Warranty\Warranty;
use Illuminate\Foundation\Http\FormRequest;

class RestoreWarrantyRequest extends FormRequest
{
    /**
     * Warranty model class container
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

        return $this->user()
            ->fresh()
            ->can('restore-warranty', $warranty);
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
        $id = $this->input('id') ?? $this->input('warranty_id');

        return $this->warranty = Warranty::onlyTrashed()->findOrFail($id);
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
