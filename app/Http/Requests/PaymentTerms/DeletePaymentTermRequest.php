<?php

namespace App\Http\Requests\PaymentTerms;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\PaymentTerm;

class DeletePaymentTermRequest extends FormRequest
{
    private $paymentTerm;

    public function getPaymentTerm()
    {
        if ($this->paymentTerm) return $this->paymentTerm;

        $id = $this->input('id') ?: $this->input('payment_term_id');
        return $this->paymentTerm = PaymentTerm::withTrashed()->findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $term = $this->getPaymentTerm();
        return Gate::allows('delete-payment-term', $term);
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
