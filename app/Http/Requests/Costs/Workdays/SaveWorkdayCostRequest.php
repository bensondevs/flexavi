<?php

namespace App\Http\Requests\Costs\Workdays;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\InputRequest;

use App\Models\Workday;

class SaveWorkdayCostRequest extends FormRequest
{
    use InputRequest;

    /**
     * Found workday model container
     * 
     * @var \App\Models\Workday|null
     */
    private $workday;

    /**
     * Find Workday or abort 404
     * 
     * @return \App\Models\Workday
     */
    public function getWorkday()
    {
        if ($this->workday) return $this->workday;

        $id = $this->input('id');
        return $this->workday = Workday::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create-cost');
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
