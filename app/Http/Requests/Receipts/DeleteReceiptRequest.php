<?php

namespace App\Http\Requests\Receipts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\InputRequest;

use App\Models\Receipt;

class DeleteReceiptRequest extends FormRequest
{
    private $receipt;

    public function getReceipt()
    {
        if ($this->receipt) return $this->receipt;

        $id = $this->input('id') ?: $this->input('receipt_id');
        return $this->receipt = Receipt::findOrFail($id);
    }

    protected function prepareForValidation()
    {
        $force = strtobool($this->input('force'));
        $this->merge(['force' => $force]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $receipt = $this->getReceipt();
        return Gate::allows('delete-receipt', $receipt);
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
