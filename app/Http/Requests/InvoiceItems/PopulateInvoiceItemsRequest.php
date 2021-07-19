<?php

namespace App\Http\Requests\InvoiceItems;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\PopulateRequestOptions;

class PopulateInvoiceItemsRequest extends FormRequest
{
    use PopulateRequestOptions;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('view-any-invoice-item');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'invoice_id' => ['required', 'string', 'exists:invoices,id'],
        ];
    }

    public function options()
    {
        $this->addWhere([
            'column' => 'invoice_id',
            'value' => $this->get('invoice_id'),
        ]);

        return $this->collectOptions();
    }
}
