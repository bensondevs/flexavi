<?php

namespace App\Http\Requests\Company\Receipts;

use App\Models\Receipt\Receipt;
use Illuminate\Foundation\Http\FormRequest;

class RestoreReceiptRequest extends FormRequest
{
    /**
     * Found receipt class container
     *
     * @var Receipt|null
     */
    private $receipt;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $receipt = $this->getReceipt();

        return $this->user()
            ->fresh()
            ->can('restore-receipt', $receipt);
    }

    /**
     * Get Receipt based on supplied input
     *
     * @return Receipt
     */
    public function getReceipt()
    {
        if ($this->receipt) {
            return $this->receipt;
        }
        $id = $this->input('id') ?: $this->input('receipt_id');

        return $this->receipt = Receipt::withTrashed()->findOrFail($id);
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
