<?php

namespace App\Http\Requests\Company\Addresses;

use App\Enums\Address\AddressType;
use App\Traits\AddressableRequest;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $user = $this->user()->fresh();
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $address = $this->getAddress();
            return $user->can('edit-address', [$address, $addressable]);
        }

        return $user->can('create-address', $addressable);
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
                Rule::in(AddressType::getValues()),
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
