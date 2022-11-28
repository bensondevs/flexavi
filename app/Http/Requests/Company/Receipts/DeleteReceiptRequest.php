<?php

namespace App\Http\Requests\Company\Receipts;

use App\Models\Receipt\Receipt;
use Illuminate\Foundation\Http\FormRequest;

class DeleteReceiptRequest extends FormRequest
{
    /**
     * Deleted receipt target
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
        $user = $this->user()->fresh();
        return $this->input('force', false)
            ? $user->can('force-delete-receipt', $receipt)
            : $user->can('delete-receipt', $receipt);
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

        return $this->receipt = Receipt::findOrFail($id);
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
     * Prepare input before validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $force = strtobool($this->input('force'));
        $this->merge(['force' => $force]);
    }
}
