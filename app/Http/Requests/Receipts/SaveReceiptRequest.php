<?php

namespace App\Http\Requests\Receipts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\InputRequest;
use App\Rules\{ Base64Image, Base64MaxSize };
use App\Models\{ Receipt, Workday, Worklist, Appointment };

class SaveReceiptRequest extends FormRequest
{
    use InputRequest;

    /**
     * Found receipt model container
     * 
     * @var  \App\Models\Receipt
     */
    private $receipt;

    /**
     * Workday found container
     * 
     * @var  \App\Models\Workday
     */
    private $workday;

    /**
     * Worklist found container
     * 
     * @var  \App\Models\Worklist
     */
    private $worklist;

    /**
     * Appointment found container
     * 
     * @var  \App\Models\Appointment
     */
    private $appointment;

    /**
     * Receiptable found container
     * 
     * @var  \App\Models\Receiptable
     */
    private $receiptable;

    /**
     * Get receipt to be updated and 
     * set the value of receiptable
     * 
     * @return \App\Models\Receipt|abort 404
     */
    public function getReceipt()
    {
        if ($this->receipt) return $this->receipt;

        $id = $this->input('id') ?: $this->input('receipt_id');
        $receipt = Receipt::with('receiptable')->findOrFail($id);
        $this->receiptable = $receipt->receiptable;

        return $receipt;
    }

    /**
     * Get receiptable model
     * 
     * @return \App\Models
     */
    public function getReceiptable()
    {
        if ($this->receiptable) return $this->receiptable;

        switch (true) {
            case $this->has('workday_id'):
                $id = $this->input('workday_id');
                $type = Workday::class;
                break;

            case $this->has('worklist_id'):
                $id = $this->input('worklist_id');
                $type = Worklist::class;
                break;

            case $this->has('appointment_id'):
                $id = $this->input('appointment_id');
                $type = Appointment::class;
                break;
            
            default:
                $id = $this->input('revenueable_id');
                $type = $this->input('revenueable_type');
                break;
        }

        $type = Revenue::guessType($type);
        return $type->findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $receiptable = $this->getReceiptable();

        if (! $this->isMethod('POST')) {
            $receipt = $this->getReceipt();
            return Gate::allows('edit-receipt', [$receipt, $receiptable]);
        }

        return Gate::allows('create-receipt', $receiptable);
    }

    protected function prepareForValidation()
    {
        $this->replace(array_filter($this->all()));

        if ($this->input('id') || $this->input('receipt_id')) {
            $this->getReceipt();
        }

        if ($receiptable = $this->getReceiptable()) {
            $this->merge([
                'company_id' => $receiptable->company_id,
                'receiptable_id' => $receiptable->id,
                'receiptable_type' => get_class($receiptable),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'company_id' => ['required', 'string'],

            'receiptable_type' => ['required', 'string'],
            'receiptable_id' => ['required', 'string'],

            'receipt_image' => ['file', 'max:5126', 'mimes:png,jpg,jpeg,svg'],
            
            'description' => ['string'],
        ]);

        if (is_base64_string($this->input('receipt_image'))) {
            $this->addRule('receipt_image', [
                new Base64Image, 
                new Base64MaxSize(5126000)
            ]);
        }

        return $this->returnRules();
    }
}
