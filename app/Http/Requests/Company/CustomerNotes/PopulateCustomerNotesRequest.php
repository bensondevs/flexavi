<?php

namespace App\Http\Requests\Company\CustomerNotes;

use App\Models\Customer\Customer;
use App\Traits\PopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class PopulateCustomerNotesRequest extends FormRequest
{
    use PopulateRequestOptions;

    /**
     * Customer instance container property
     *
     * @var Customer|null
     */
    private ?Customer $customer = null;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->fresh()->can('view-any-customer-note');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    /**
     * Get options of request
     *
     * @return array
     */
    public function options(): array
    {
        $customer = $this->getCustomer();

        $this->addWhere([
            'column' => 'customer_id',
            'value' => $customer->id
        ]);

        $this->addOrderBy('created_at', 'DESC');

        return $this->collectOptions();
    }

    /**
     * Get Customer based on the supplied input
     *
     * @param bool $force
     * @return Customer|null
     */
    public function getCustomer(bool $force = false): ?Customer
    {
        if ($this->customer instanceof Customer and not($force)) {
            return $this->customer;
        }

        $id = $this->input('customer_id');
        return $this->customer = Customer::findOrFail($id);
    }
}
