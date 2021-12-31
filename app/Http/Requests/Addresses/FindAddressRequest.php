<?php

namespace App\Http\Requests\Addresses;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\{
    AddressableRequest, 
    RequestHasRelations
};
use App\Models\Address;

class FindAddressRequest extends FormRequest
{
    use AddressableRequest;
    use RequestHasRelations;

    /**
     * List of configurable relationships
     * 
     * @var array
     */
    private $relationNames = [
        'with_addressable' => true,
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $address = $this->getAddress();
        return Gate::allows('view-address', $address);
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
