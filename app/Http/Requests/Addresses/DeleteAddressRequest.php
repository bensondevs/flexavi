<?php

namespace App\Http\Requests\Addresses;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\AddressableRequest;
use App\Traits\RequestHasRelations;
use App\Traits\PopulateRequestOptions;

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
        return $this->input('force') ?
            Gate::allows('force-delete-address', [$address, $addressable]) :
            Gate::allows('delete-address', [$address, $addressable]);
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
