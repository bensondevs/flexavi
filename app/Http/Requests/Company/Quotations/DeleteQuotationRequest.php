<?php

namespace App\Http\Requests\Company\Quotations;

use App\Models\Quotation\Quotation;
use Illuminate\Foundation\Http\FormRequest;

class DeleteQuotationRequest extends FormRequest
{
    /**
     * Target quotation model container
     *
     * @var Quotation|null
     */
    private ?Quotation $quotation = null;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $quotation = $this->getQuotation();
        $user = $this->user()->fresh();

        return $this->input('force')
            ? $user->can('force-delete-quotation', $quotation)
            : $user->can('delete-quotation', $quotation);
    }

    /**
     * Get Quotation based on supplied input
     *
     * @return Quotation|null
     */
    public function getQuotation(): ?Quotation
    {
        if ($this->quotation) {
            return $this->quotation;
        }

        $id = $this->input('quotation_id');
        $quotation = new Quotation();

        if (strtobool($this->input('force'))) {
            $quotation = $quotation->onlyTrashed();
        }
        return $this->quotation = $quotation->findOrFail($id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Format input values before validations
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $force = strtobool($this->input('force'));
        $this->merge(['force' => $force]);
    }
}
