<?php

namespace App\Http\Requests\Company\Addresses;

use App\Traits\AddressableRequest;
use Illuminate\Foundation\Http\FormRequest;

class DeleteAddressRequest extends FormRequest
{
    use AddressableRequest;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $address = $this->getAddress();
        $addressable = $this->getAddressable();
        $user = $this->user()->fresh();
        return $this->input('force')
            ? $user->can('force-delete-address', [$address, $addressable])
            : $user->can('delete-address', [$address, $addressable]);
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

    /**
     * Prepare input requests before validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $force = $this->input('force');
        $this->merge(['force' => strtobool($force)]);
    }
}
