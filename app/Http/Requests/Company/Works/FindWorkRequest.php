<?php

namespace App\Http\Requests\Company\Works;

use App\Models\Work\Work;
use Illuminate\Foundation\Http\FormRequest;

class FindWorkRequest extends FormRequest
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
            ->can('view-work', $work);
    }

    /**
     * Get Work based on supplied input
     *
     * @return Work
     */
    public function getWork()
    {
        if ($this->work) {
            return $this->work;
        }
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
