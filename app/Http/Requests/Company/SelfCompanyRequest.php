<?php

namespace App\Http\Requests\Company;

use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;

class SelfCompanyRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $user = $this->user()->refresh();

        return $user->roles()->exists(); // Has role to view company
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
