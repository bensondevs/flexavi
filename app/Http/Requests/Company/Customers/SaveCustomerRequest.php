<?php

namespace App\Http\Requests\Company\Customers;

use App\Enums\Customer\{CustomerAcquisition, CustomerSalutation};
use App\Models\Customer\Customer;
use App\Rules\{NotEqual};
use App\Services\Pro6PP\Pro6PPService;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SaveCustomerRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Customer object
     *
     * @var Customer|null
     */
    private $customer;

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
        return $this->user()
            ->fresh()
            ->can('create-customer');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'salutation' => [
                'required',
                Rule::in(CustomerSalutation::getValues()),
            ],
            'fullname' => ['required', 'string', 'regex:/^[\pL\s\-]+$/u'],
            'email' => ['string', 'email', 'unique:customers,email'],
            'phone' => ['required', 'numeric', 'unique:customers,phone'],
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
            'company_id' => $this->input('company_id'),
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
        $address = [
        'house_number' => $this->get('house_number'),
        'zipcode' => $this->get('zipcode'),
        ];

        if (!$this->has('address')) {
            $pro6ppAddress = $this->pro6ppAddress;

            if ($this->pro6ppAddressStatusCode == 200) {
                $address = array_merge(
                    $address,
                    [
                        'address' =>  $pro6ppAddress->street,
                        'city' => $pro6ppAddress->settlement,
                        'province' => $pro6ppAddress->province,
                        'latitude' => $pro6ppAddress->lat,
                        'longitude' => $pro6ppAddress->lng,
                    ]
                );
            }
        } else {
            $address = array_merge(
                $address,
                [
                    'address' => $this->get('address'),
                    'house_number_suffix' => $this->get('house_number_suffix'),
                    'city' => $this->get('city'),
                    'province' => $this->get('province'),
                ]
            );
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
        $company = $this->getCompany();
        $this->merge(['company_id' => $company->id]);

        if (is_null($this->input('address')) or $this->input('address') == "") {
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
                'house_number' => 'The House number is invalid'
            ]);
        }
    }
}
