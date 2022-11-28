<?php

namespace App\Http\Requests\Company\ExecuteWorks;

use App\Models\ExecuteWork\ExecuteWork;
use App\Traits\RequestHasRelations;
use Illuminate\Foundation\Http\FormRequest;

class FindExecuteWorkRequest extends FormRequest
{
    use RequestHasRelations;

    /**
     * List of loadable relationships
     *
     * @var array
     */
    private $relationNames = [
        'with_appointment' => false,
        'with_company' => false,
        'with_photos' => false
    ];

    /**
     * Found ExecuteWork model container
     *
     * @var ExecuteWork|null
     */
    private $executeWork;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $executeWork = $this->getExecuteWork();

        return $this->user()
            ->fresh()
            ->can('view-execute-work', $executeWork);
    }

    /**
     * Get ExecuteWork based on supplied input
     *
     * @return ExecuteWork
     */
    public function getExecuteWork()
    {
        if ($this->executeWork) {
            return $this->executeWork;
        }
        $id = $this->input('id') ?: $this->input('execute_work_id');
        $relations = $this->relations();
        $newRelations = [];
        foreach ($relations as $relation) {
            if ($relation == "photos") {
                array_push($newRelations, $relation . ".works");
            } else {
                array_push($newRelations, $relation);
            }
        }


        return $this->executeWork = ExecuteWork::with($newRelations)->findOrFail($id);
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

    /**
     * Prepare input for validation.
     *
     * This will prepare input to configure the loadable relationships
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->prepareRelationInputs();
    }
}
