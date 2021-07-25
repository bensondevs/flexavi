<?php

namespace App\Http\Requests\Addresses;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\PopulateRequestOptions;

use App\Models\User;

class PopulateUserAddressesRequest extends FormRequest
{
    use PopulateRequestOptions;

    private $user;

    public function getUser()
    {
        if ($this->user) return $this->user;

        if (! $id = ($this->input('id') ?: $this->input('user_id'))) {
            return $this->user();
        }

        return $this->user = User::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
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

    public function options()
    {
        $this->addWhere([
            'column' => 'user_id',
            'value' => $this->getUser()->id,
        ]);

        return $this->collectOptions();
    }
}
