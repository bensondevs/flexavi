<?php

namespace App\Http\Requests\Company\WorkContracts;

use App\Models\WorkContract\WorkContract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NullifyWorkContractRequest extends FormRequest
{
    /**
     * Solution instance container property.
     *
     * @var WorkContract|null
     */
    private ?WorkContract $workContract = null;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->fresh()->can('nullify-work-contract', $this->getWorkContract());
    }

    /**
     * Get work contract based on the supplied input
     *
     * @return WorkContract|null
     */
    public function getWorkContract(): ?WorkContract
    {
        if ($this->workContract) {
            return $this->workContract;
        }

        $id = $this->input('work_contract_id');
        return $this->workContract = WorkContract::findOrFail($id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'work_contract_id' => ['required', 'string', Rule::exists('work_contracts', 'id')],
        ];
    }
}
