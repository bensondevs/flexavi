<?php

namespace App\Http\Requests\Company\Works;

use App\Models\Work\Work;
use Illuminate\Foundation\Http\FormRequest;

class RestoreWorkRequest extends FormRequest
{
    /**
     * Work object
     *
     * @var Work|null
     */
    private $work;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $work = $this->getWork();

        return $this->user()
            ->fresh()
            ->can('restore-work', $work);
    }

    /**
     * Get Trashed Work based on supplied input
     *
     * @return Work
     */
    public function getWork()
    {
        if ($this->work) {
            return $this->work;
        }
        $id = $this->input('id') ?: $this->input('work_id');

        return $this->work = Work::withTrashed()->findOrFail($id);
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
