<?php

namespace App\Http\Requests\Invoices;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Invoice;

class FindInvoiceRequest extends FormRequest
{
    private $invoice;

    public function getInvoice()
    {
        return $this->invoice = $this->invoice ?: 
            Invoice::findOrFail($this->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        $invoice = $this->getInvoice();

        return $user->hasCompanyPermission($invoice, 'view invoices');
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
