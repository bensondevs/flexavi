<?php

namespace App\Http\Requests\Company\Receipts;

use App\Models\{Appointment\Appointment, Receipt\Receipt, Revenue\Revenue, Workday\Workday, Worklist\Worklist};
use App\Rules\Helpers\Media;
use App\Traits\InputRequest;
use Illuminate\Foundation\Http\FormRequest;

class SaveReceiptRequest extends FormRequest
{
    use InputRequest;

    /**
     * Found receipt model container
     *
     * @var Receipt|null
     */
    private $receipt;

    /**
     * Receiptable found container
     *
     * @var mixed
     */
    private $receiptable;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $receiptable = $this->getReceiptable();
        $user = $this->user()->fresh();
        if (!$this->isMethod('POST')) {
            $receipt = $this->getReceipt();
            return $user->can('edit-receipt', [$receipt, $receiptable]);
        }

        return $user->can('create-receipt', $receiptable);
    }

    /**
     * Get receiptable model
     *
     * @return mixed
     */
    public function getReceiptable()
    {
        if ($this->receiptable) {
            return $this->receiptable;
        }
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

        return $this->receiptable = $type->findOrFail($id);
    }

    /**
     * Get receipt to be updated and set
     * the value of receiptable
     *
     * @return Receipt
     */
    public function getReceipt()
    {
        if ($this->receipt) {
            return $this->receipt;
        }
        $id = $this->input('id') ?: $this->input('receipt_id');
        $receipt = Receipt::with('receiptable')->findOrFail($id);
        $this->receiptable = $receipt->receiptable;

        return $this->receipt = $receipt;
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
            'receipt_image' => [
                'required',
                'image',
                'max:' . Media::MAX_IMAGE_SIZE,
                'mimes:' . Media::imageExtensions(),
            ],
            'description' => ['string'],
        ]);

        return $this->returnRules();
    }

    /**
     * Prepare for validation
     *
     * @return void
     */
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
}
