<?php

namespace App\Http\Requests\Company\InvoiceSettings;

use App\Enums\Invoice\InvoiceReminderSentType;
use App\Models\Invoice\Invoice;
use App\Models\Setting\InvoiceSetting;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class UpdateInvoiceSettingRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Found invoice setting from get function execution
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
        return $this->user()->fresh()->can('edit-invoice-reminder', $invoice);
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
     * Prepare the data for validation.
     *
     * @return void
     */
    public function prepareForValidation(): void
    {
        $this->merge([
            'auto_reminder_activated' => strtobool($this->input('auto_reminder_activated')),
        ]);
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

            'auto_reminder_activated' => ['required', 'bool'],
            'first_reminder_type' => ['required', 'numeric', Rule::in(InvoiceReminderSentType::getValues())],

            'second_reminder_days' => ['required', 'numeric', 'min:0'],
            'second_reminder_type' => ['required', 'numeric', Rule::in(InvoiceReminderSentType::getValues())],

            'third_reminder_days' => ['required', 'numeric', 'min:0'],
            'third_reminder_type' => ['required', 'numeric', Rule::in(InvoiceReminderSentType::getValues())],

            'debt_collector_reminder_days' => ['required', 'numeric', 'min:0'],
            'debt_collector_reminder_type' => ['required', 'numeric', Rule::in(InvoiceReminderSentType::getValues())],


        ];
    }
}
