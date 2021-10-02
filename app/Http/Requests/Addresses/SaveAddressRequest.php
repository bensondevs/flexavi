<?php

namespace App\Http\Requests\Addresses;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\AddressableRequest;
use App\Traits\CompanyInputRequest;

use App\Enums\Address\AddressType;

class SaveAddressRequest extends FormRequest
{
    use AddressableRequest;
    use CompanyInputRequest;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $addressable = $this->getAddressable();
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $address = $this->getAddress();
            return Gate::allows('edit-address', [$address, $addressable]);
        }

        return Gate::allows('create-address', $addressable);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'address_type' => [
                'required', 
                'numeric', 
                'min:' . AddressType::VisitingAddress, 
                'max:' . AddressType::Other
            ],
            'other_address_type_description' => [
                'required_if:address_type,' . AddressType::Other,
                'nullable',
                'string',
            ],
            'address' => ['required', 'string'],
            'house_number' => ['required', 'numeric'],
            'house_number_suffix' => ['nullable', 'string'],
            'zipcode' => ['required', 'numeric'],
            'city' => ['required', 'string'],
            'province' => ['required', 'string'],
        ]);

        return $this->returnRules();
    }
}
