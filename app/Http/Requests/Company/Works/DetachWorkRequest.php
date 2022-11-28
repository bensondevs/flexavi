<?php

namespace App\Http\Requests\Company\Works;

use App\Traits\WorkableRequest;
use Illuminate\Foundation\Http\FormRequest;

class DetachWorkRequest extends FormRequest
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
            ->can('detach-work', [$work, $workable]);
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
