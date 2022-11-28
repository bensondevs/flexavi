<?php

namespace App\Http\Requests\Company\WorkContracts;

use App\Models\WorkContract\WorkContract;
use Illuminate\Foundation\Http\FormRequest;

class DeleteWorkContractRequest extends FormRequest
{
    /**
     * work contract instance container property.
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
        $user = $this->user()->fresh();
        $workContract = $this->getWorkContract();
        return strtobool($this->input('force'))
            ? $user->can('delete-work-contract', $workContract)
            : $user->can('force-delete-work-contract', $workContract);
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
        $workContract = (new WorkContract());
        if (strtobool($this->input('force'))) {
            $workContract = $workContract->onlyTrashed();
        }
        return $this->workContract = $workContract->findOrFail($id);
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
