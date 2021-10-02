<?php

namespace App\Http\Requests\Addresses;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Address;

class RestoreAddressRequest extends FormRequest
{
    private $address;

    public function getTrashedAddress()
    {
        if ($this->address) return $this->address;

        $id = $this->input('id') ?: $this->input('address_id');
        return $this->address = Address::onlyTrashed()->findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $address = $this->getTrashedAddress();
        $addressable = $address->addressable;
        return Gate::allows('restore-address', [$address, $addressable]);
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
