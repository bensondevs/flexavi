<?php

namespace App\Http\Requests\Company\Invoices;

use App\Models\Invoice\Invoice;
use App\Traits\CompanyInputRequest;
use App\Traits\InputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ChangeInvoiceStatusRequest extends FormRequest
{
    use InputRequest, CompanyInputRequest;

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
            ->can('change-status-invoice', [$invoice, $this->input('status')]);
    }

    /**
     * Get Invoice based on supplied input
     *
     * @return Invoice|null
     */
    public function getInvoice(): ?Invoice
    {
        if ($this->invoice) {
            return $this->invoice;
        }
        $id = $this->input('invoice_id');

        return $this->invoice = Invoice::findOrFail($id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function rules(): array
    {
        $company = $this->getCompany();
        return [
            'invoice_id' => ['required', 'string', Rule::exists('invoices', 'id')->where('company_id', $company->id)],
            'status' => [
                'required',
                'numeric',
                Rule::in(array_keys(Invoice::collectStatusOptions()))
            ],
        ];
    }
}
