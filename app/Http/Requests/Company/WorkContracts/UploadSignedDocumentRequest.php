<?php

namespace App\Http\Requests\Company\WorkContracts;

use App\Models\WorkContract\WorkContract;
use App\Rules\Helpers\Media;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class UploadSignedDocumentRequest extends FormRequest
{
    use CompanyInputRequest;

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
        return $this->user()->fresh()->can('upload-signed-document-work-contract', $this->getWorkContract());
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
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function rules(): array
    {
        $company = $this->getCompany();
        return [
            'work_contract_id' => ['required', 'string', Rule::exists('work_contracts', 'id')->where('company_id', $company->id)],
            'signed_document' => ['required', 'file', 'mimes:' . Media::documentExtensions() . ',' . Media::imageExtensions(), 'max:' . Media::MAX_DOCUMENT_SIZE],
        ];
    }
}
