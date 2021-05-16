<?php

namespace App\Http\Requests\Inspectors;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\InputRequest;

class SaveInspectorRequest extends FormRequest
{
    use InputRequest;

    private $inspector;
    private $inspection;

    public function getInspector()
    {
        return $this->inspector = $this->model = $this->inspector ?:
            Inspector::findOrFail($this->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'inspection_id' => [
                'required', 
                'string', 
                'exists:inspections,id'
            ],
            'user_id' => [
                'required', 
                'string', 
                'exists:users,id'
            ],
        ]);

        return $this->returnRules();
    }

    public function onlyInRules()
    {
        return $this->only(array_keys($this->rules()));
    }
}
