<?php

namespace App\Http\Requests\Company\Invoices;

use App\Models\Invoice\Invoice;
use App\Traits\RequestHasRelations;
use Illuminate\Foundation\Http\FormRequest;

class FindInvoiceRequest extends FormRequest
{
    use RequestHasRelations;

    /**
     * List of configurable relationships
     *
     * @var array
     */
    protected array $relationNames = [
        'with_customer' => true,
        'with_items.workService' => false,
    ];

    /**
     * Found invoice from get function execution
     *
     * @var Invoice|null
     */
    private ?Invoice $invoice = null;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $invoice = $this->getInvoice();

        return $this->user()
            ->fresh()
            ->can('view-invoice', $invoice);
    }

    /**
     * Get invoice from supplied parameter of `id` or `invoice_id`
     *
     * @return Invoice|null
     */
    public function getInvoice(): ?Invoice
    {
        if ($this->invoice) {
            return $this->invoice;
        }
        $id = $this->input('invoice_id');
        $relations = $this->getRelations();
        return $this->invoice = Invoice::with($relations)->findOrFail($id);
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
}
