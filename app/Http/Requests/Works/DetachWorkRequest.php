<?php

namespace App\Http\Requests\Works;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\WorkableRequest;

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

        return Gate::allows('detach-work', [$work, $workable]);
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
