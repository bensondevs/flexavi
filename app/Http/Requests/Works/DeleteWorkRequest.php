<?php

namespace App\Http\Requests\Works;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Work;

class DeleteWorkRequest extends FormRequest
{
    private $work;

    public function getWork()
    {
        if ($this->work) return $this->work;

        $id = $this->input('id');
        return $this->work = Work::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $work = $this->getWork();

        $action = $this->input('force') ? 'force-delete-work' : 'delete-work';
        return Gate::allows($action, $work);
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
