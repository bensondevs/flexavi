<?php

namespace App\Http\Requests\Company\Works;

use App\Traits\WorkableRequest;
use Illuminate\Foundation\Http\FormRequest;

class AttachWorkRequest extends FormRequest
{
    use WorkableRequest;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $work = $this->getWork();
        $workable = $this->getWorkable();

        return $this->user()
            ->fresh()
            ->can('attach-work', [$work, $workable]);
    }

    /**
     * Prepare for validation.
     *
     * @return void
     */
    public function prepareForValidation()
    {
        if ($this->has('work_ids')) {
            if (!is_array($this->input('work_ids'))) {
                $workIds = $this->input('work_ids');
                $workIds = json_decode($workIds, true);
                $this->merge(['work_ids' => $workIds]);
            }
        }
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
}
