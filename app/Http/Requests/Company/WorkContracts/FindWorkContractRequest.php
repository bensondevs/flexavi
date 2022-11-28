<?php

namespace App\Http\Requests\Company\WorkContracts;

use App\Models\WorkContract\WorkContract;
use App\Traits\RequestHasRelations;
use Illuminate\Foundation\Http\FormRequest;

class FindWorkContractRequest extends FormRequest
{
    use RequestHasRelations;

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
        return $this->user()->fresh()->can("view-work-contract", $this->getWorkContract());

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
        return $this->workContract = WorkContract::with('services.workService', 'forewordContents', 'contractContents', 'signatures')->findOrFail($id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [

        ];
    }

    /**
     * prepare the inputs request for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        //
    }
}
