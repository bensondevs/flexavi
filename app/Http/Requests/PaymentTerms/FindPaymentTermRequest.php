<?php

namespace App\Http\Requests\PaymentTerms;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\PaymentTerm;

class FindPaymentTermRequest extends FormRequest
{
    private $paymentTerm;

    public function getPaymentTerm()
    {
        return $this->paymentTerm = $this->paymentTerm ?:
            PaymentTerm::findOrFail($this->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        $term = $this->getPaymentTerm();
        $invoice = $term->invoice;

        return $user->hasCompanyPermission(
            $invoice->company_id, 
            'view payment terms'
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
