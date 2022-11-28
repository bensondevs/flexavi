<?php

namespace App\Http\Requests\Company\Customers;

use App\Enums\Customer\{CustomerAcquisition, CustomerSalutation};
use App\Models\{Address\Address, Customer\Customer};
use App\Rules\NotEqual;
use App\Services\Pro6PP\Pro6PPService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UpdateCustomerRequest extends FormRequest
{
    /**
     *
     * Customer object
     *
     * @var Customer|null
     */
    private $customer;

    /**
     *
     * Address object
     *
     * @var Address|null
     */
    private $address;

    /**
     * Result of Pro6PP Api
     *
     */
    private $pro6ppAddress;

    /**
     * Result of Pro6PP Api Status
     *
     */
    private $pro6ppAddressStatusCode;

    /**
     * Get Address based on supplied input
     *
     * @return Address
     */
    public function getCustomerAddress()
    {
        if ($this->address) {
            return $this->address;
        }
        $customer = $this->getCustomer();

        return $this->address = $customer->address;
    }

    /**
     * Get Customer based on supplied input
     *
     * @return Customer
     */
    public function getCustomer()
    {
        if ($this->customer) {
            return $this->customer;
        }
        $id = $this->input('id') ?: $this->input('customer_id');

        return $this->customer = $this->model = Customer::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $customer = $this->getCustomer();

        return $this->user()
            ->fresh()
            ->can('edit-customer', $customer);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => ['required', 'exists:customers,id'],
            'salutation' => [
                'required',
                Rule::in(CustomerSalutation::getValues()),
            ],
            'fullname' => ['required', 'string'],
            'email' => [
                'string',
                'email',
                Rule::unique('customers', 'email')->ignore($this->input('id')),
            ],
            'phone' => [
                'required',
                'numeric',
                Rule::unique('customers', 'phone')->ignore($this->input('id')),
            ],
            'second_phone' => ['numeric', new NotEqual('phone')],
            'acquired_through' => [
                'nullable',
                Rule::in(CustomerAcquisition::getValues()),
            ],
            'acquired_by' => ['nullable', 'exists:users,id'],
            'address' => ['nullable', 'string'],
            'house_number' => ['required', 'string'],
            'house_number_suffix' => ['nullable', 'string'],
            'zipcode' => ['required', 'string'],
            'city' => ['nullable', 'string'],
            'province' => ['nullable', 'string'],
        ];
    }

    /**
     * Get the customer data
     *
     * @return array
     */
    public function customerData()
    {
        return [
            'salutation' => $this->input('salutation'),
            'fullname' => $this->input('fullname'),
            'email' => $this->input('email'),
            'phone' => $this->input('phone'),
            'second_phone' => $this->input('second_phone'),
            'acquired_through' =>
                $this->input('acquired_through') ?:
                    CustomerAcquisition::Website,
            'acquired_by' => $this->input('acquired_by'),
        ];
    }

    /**
     * Get the address data
     *
     * @return array
     */
    public function addressData()
    {
        $pro6ppAddress = $this->pro6ppAddress;

        $address = [
            'house_number' => $this->input('house_number'),
            'zipcode' => $this->input('zipcode'),
            'address' => null,
            'city' => null,
            'province' => null,
            'latitude' => null,
            'longitude' => null,
        ];

        if (is_null($this->input('address')) or $this->input('address') == '') {
            if ($this->pro6ppAddressStatusCode == 200) {
                $address['address'] = $pro6ppAddress->street;
                $address['city'] = $pro6ppAddress->settlement;
                $address['province'] = $pro6ppAddress->province;
                $address['latitude'] = $pro6ppAddress->lat;
                $address['longitude'] = $pro6ppAddress->lng;
            }
        } else {
            $address['address'] = $this->input('address');
            $address['city'] = $this->input('city');
            $address['province'] = $this->input('province');
        }

        return $address;
    }

    /**
     * Prepare input for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (is_null($this->input('address')) or $this->input('address') == '') {
            $this->getPro6ppAddress();
            $this->customThrowException();
        }
    }

    /**
     * Get Address from Pro6PP Api
     *
     * @return Customer
     */
    private function getPro6ppAddress()
    {
        if ($this->pro6ppAddress) {
            return $this->pro6ppAddress;
        }
        $pro6ppService = new Pro6PPService();
        $this->pro6ppAddress = $pro6ppService->autocomplete([
            'zipcode' => $this->input('zipcode'),
            'house_number' => $this->input('house_number'),
        ]);
        $this->pro6ppAddressStatusCode = $pro6ppService->responseServiceCode;

        return $this->pro6ppAddress;
    }

    /**
     * Custom throw exception to handle response from Pro6pp
     *
     * @return void
     */
    private function customThrowException()
    {
        if ($this->pro6ppAddressStatusCode == 404) {
            throw ValidationException::withMessages([
                'zipcode' => 'The Zipcode is invalid',
                'house_number' => 'The House number is invalid',
            ]);
        }
    }
}
