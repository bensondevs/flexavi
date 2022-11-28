<?php

namespace App\Http\Requests\Company;

use App\Services\Pro6PP\Pro6PPService;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class StoreCompanyRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user()->fresh();
        return $user->can('create-company');
    }

    /**
     * Prepare input for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $pro6ppService = app(Pro6PPService::class);
        if ( // check if the provided address are only 'house_number' and 'zipcode'
            (!$this->has('visiting_address.address'))
            &&
            ($this->has('visiting_address.house_number')
            && $this->has('visiting_address.zipcode'))
        ) {
            $visitingAddress = Arr::only(
                $this->get('visiting_address'),
                ['house_number', 'zipcode']
            ) ;
            $pro6ppAddress = $pro6ppService->autocomplete($visitingAddress);

            if (isset($pro6ppAddress->errors)) {
                abort('422', $pro6ppAddress->errors[0]->message);
            }

            $this->merge([
                'visiting_address' => array_merge($visitingAddress, [
                    'address' =>  $pro6ppAddress->street,
                    'city' => $pro6ppAddress->settlement,
                    'province' => $pro6ppAddress->province,
                    'latitude' => $pro6ppAddress->lat,
                    'longitude' => $pro6ppAddress->lng,
                    'house_number_suffix' => strval($visitingAddress['house_number']),
                ])
            ]);
        }

        if ( // check if the provided address are only 'house_number' and 'zipcode'
            (!$this->has('invoicing_address.address'))
            &&
            ($this->has('invoicing_address.house_number')
            && $this->has('invoicing_address.zipcode'))
        ) {
            $invoicingAddress = Arr::only(
                $this->get('invoicing_address'),
                ['house_number', 'zipcode']
            ) ;
            $pro6ppAddress = $pro6ppService->autocomplete($invoicingAddress);

            if (isset($pro6ppAddress->errors)) {
                abort('422', $pro6ppAddress->errors[0]->message);
            }

            $this->merge([
                'invoicing_address' => array_merge($invoicingAddress, [
                    'address' =>  $pro6ppAddress->street,
                    'city' => $pro6ppAddress->settlement,
                    'province' => $pro6ppAddress->province,
                    'latitude' => $pro6ppAddress->lat,
                    'longitude' => $pro6ppAddress->lng,
                    'house_number_suffix' => strval($invoicingAddress['house_number']),
                ])
            ]);
        }
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'company_name' => 'required|string' ,
            'email' => 'required|email' ,
            'phone_number' => 'required|string' ,
            'vat_number' => 'required|numeric' ,
            'commerce_chamber_number' => 'required|numeric' ,
            'company_website_url' => 'nullable|string' ,

            'visiting_address.address' => 'required|string' ,
            'visiting_address.house_number' => 'required|numeric' ,
            'visiting_address.house_number_suffix' => 'required|string' ,
            'visiting_address.province' => 'required|string' ,
            'visiting_address.zipcode' => 'required|string' ,
            'visiting_address.city' => 'required|string' ,
            'visiting_address.latitude' => 'nullable|numeric' ,
            'visiting_address.longitude' => 'nullable|numeric' ,

            'invoicing_address.address' => 'nullable|string' ,
            'invoicing_address.house_number' => 'nullable|numeric' ,
            'invoicing_address.house_number_suffix' => 'required|string' ,
            'invoicing_address.province' => 'required|string' ,
            'invoicing_address.zipcode' => 'nullable|string' ,
            'invoicing_address.city' => 'nullable|string' ,
            'invoicing_address.latitude' => 'nullable|numeric' ,
            'invoicing_address.longitude' => 'nullable|numeric' ,
        ];
    }
}
