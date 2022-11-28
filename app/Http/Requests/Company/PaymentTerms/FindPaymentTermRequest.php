<?php

namespace App\Http\Requests\Company\PaymentTerms;

use App\Models\PaymentPickup\PaymentTerm;
use App\Traits\RequestHasRelations;
use Illuminate\Foundation\Http\FormRequest;

class FindPaymentTermRequest extends FormRequest
{
    use RequestHasRelations;

    /**
     * Define the relation names
     *
     * @var array
     */
    protected $relationNames = [
        'with_company' => false,
        'with_invoice' => false,
    ];

    /**
     * PaymentTerm object
     *
     * @var PaymentTerm|null
     */
    private $paymentTerm;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $term = $this->getPaymentTerm();

        return $this->user()
            ->fresh()
            ->can('view-payment-term', $term);
    }

    /**
     * Get PaymentTerm based on supplied input
     *
     * @return PaymentTerm
     */
    public function getPaymentTerm()
    {
        if ($this->paymentTerm) {
            return $this->paymentTerm;
        }
        $id = $this->input('id') ?: $this->input('payment_term_id');

        return $this->paymentTerm = PaymentTerm::findOrFail($id);
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

    /**
     * Prepare for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->prepareRelationInputs();
    }
}
