<?php

namespace App\Http\Requests\Company\Warranties;

use App\Enums\Warranty\WarrantyStatus;
use App\Models\Warranty\Warranty;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWarrantyRequest extends FormRequest
{
    use CompanyInputRequest;

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

        return $this->user()
            ->fresh()
            ->can('edit-warranty', $warranty);
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
        return [
            'status' => [
                'required',
                'numeric',
                'min:' . WarrantyStatus::Created,
                'max:' . WarrantyStatus::Unfinished,
            ],
            'problem_description' => ['nullable', 'string'],
            'fixing_description' => ['nullable', 'string'],
            'internal_note' => ['nullable', 'string'],
            'customer_note' => ['nullable', 'string'],
            'amount' => ['nullable', 'numeric'],
            'paid_amount' => ['nullable', 'numeric'],
        ];
    }
}
