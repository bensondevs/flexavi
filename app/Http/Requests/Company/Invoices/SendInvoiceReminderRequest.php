<?php

namespace App\Http\Requests\Company\Invoices;

use App\Models\Invoice\Invoice;
use App\Traits\InputRequest;
use Illuminate\Foundation\Http\FormRequest;

class SendInvoiceReminderRequest extends FormRequest
{
    use InputRequest;

    /**
     * Found invoice from get function execution
     * container
     *
     * @var Invoice|null
     */
    private $invoice;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $invoice = $this->getInvoice();

        return $this->user()
            ->fresh()
            ->can('send-reminder-invoice', $invoice);
    }

    /**
     * Get Invoice based on supplied input
     *
     * @return Invoice
     */
    public function getInvoice()
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
     */
    public function rules()
    {
        $this->setRules([
            'overdue_at' => ['nullable', 'date'],
            'destination_email' => ['required', 'email'],
            'custom_message' => ['nullable', 'string'],
        ]);

        return $this->returnRules();
    }

    /**
     * Prepare input before validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->has('overdue_at')) {
            $overdueAt = $this->input('overdue_at');
            $this->merge([
                'overdue_at' => carbon_parse_format($overdueAt, 'Y-m-d'),
            ]);
        }
        $this->merge([
            'destination_email' => $this->has('destination_email')
                ? $this->input('destination_email')
                : $this->getInvoice()->customer->email,
        ]);
    }
}
