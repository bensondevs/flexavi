<?php

namespace App\Http\Requests\Company\InvoiceSettings;

use App\Models\Invoice\Invoice;
use App\Models\Setting\InvoiceSetting;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class FindInvoiceSettingRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Found invoice from get function execution
     *
     * @var InvoiceSetting|null
     */
    private ?InvoiceSetting $invoiceSetting = null;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $invoice = Invoice::findOrFail($this->getInvoiceSetting()->invoice_id);
        return $this->user()->fresh()->can('view-any-invoice-reminder', $invoice);
    }

    /**
     * Get invoice from supplied parameter of `id` or `invoice_id`
     *
     * @return InvoiceSetting|null
     */
    public function getInvoiceSetting(): ?InvoiceSetting
    {
        if ($this->invoiceSetting) {
            return $this->invoiceSetting;
        }
        $id = $this->input('invoice_id');

        return $this->invoiceSetting = InvoiceSetting::where('invoice_id', $id)->firstOrFail();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function rules(): array
    {
        $company = $this->getCompany();
        return [
            'invoice_id' => ['required', 'string', Rule::exists('invoices', 'id')->where('company_id', $company->id)],
        ];
    }
}
