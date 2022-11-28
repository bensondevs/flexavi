<?php

namespace App\Http\Requests\Company\Addresses;

use App\Models\Address\Address;
use Illuminate\Foundation\Http\FormRequest;

class RestoreAddressRequest extends FormRequest
{
    /**
     * Address object
     *
     * @var Address|null
     */
    private $address;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $address = $this->getTrashedAddress();
        $addressable = $address->addressable;

        return $this->user()
            ->fresh()
            ->can('restore-address', [$address, $addressable]);
    }

    /**
     * Get Trashed Address based on supplied input
     *
     * @return Address
     */
    public function getTrashedAddress()
    {
        if ($this->address) {
            return $this->address;
        }
        $id = $this->input('id') ?: $this->input('address_id');

        return $this->address = Address::onlyTrashed()->findOrFail($id);
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
