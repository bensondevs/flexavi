<?php

namespace App\Http\Requests\Works;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Work;

class FindWorkRequest extends FormRequest
{
    private $work;

    public function getWork()
    {
        if ($this->work) return $this->work;

        $id = $this->input('id') ?: $this->input('work_id');

        $withs = [
            'appointments', 
            'quotations', 
            'finishedAtAppointment',
            'executeWorks',
            'currentExecuteWork',
        ];
        return $this->work = Work::with($withs)->findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $work = $this->getWork();
        return Gate::allows('view-work', $work);
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
