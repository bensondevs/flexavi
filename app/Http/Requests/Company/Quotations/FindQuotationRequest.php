<?php

namespace App\Http\Requests\Company\Quotations;

use App\Models\Quotation\Quotation;
use App\Traits\RequestHasRelations;
use Illuminate\Foundation\Http\FormRequest;

class FindQuotationRequest extends FormRequest
{
    use RequestHasRelations;

    /**
     * List of loadable relationships
     *
     * @var array
     */
    private array $relationNames = [
        'with_appointment' => false,
        'with_items.workService' => false,
        'with_customer' => true,
        'with_company' => false,
    ];

    /**
     * Found Quotation model container
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

        return $this->user()
            ->fresh()
            ->can('view-quotation', $quotation);
    }

    /**
     * Get Quotation based on supplied input
     *
     * @return ?Quotation
     */
    public function getQuotation(): ?Quotation
    {
        if ($this->quotation) {
            return $this->quotation;
        }
        $id = $this->input('quotation_id');
        $relations = $this->getRelations();

        return $this->quotation = Quotation::with($relations)->findOrFail($id);
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
     * Prepare input for validation.
     *
     * This will prepare input to configure the loadable relationships
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->prepareRelationInputs();
    }
}
