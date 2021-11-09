<?php

namespace App\Http\Requests\CarRegisterTimes;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\CompanyInputRequest;

use App\Models\{ CarRegisterTime };

class UpdateCarRegisterTimeRequest extends FormRequest
{
    use CompanyInputRequest;

    private $time;

    public function getCarRegisterTime()
    {
        if ($this->time) return $this->time;

        $id = $this->input('car_register_time_id') ?: $this->input('id');
        return $this->time = CarRegisterTime::findOrFail($id);
    }

    protected function prepareForValidation()
    {
        if ($this->has('should_out_at')) {
            $shouldOutAt = carbon($this->input('should_out_at'));
            $this->merge(['should_out_at' => $shouldOutAt]);
        }

        if ($this->has('should_return_at')) {
            $shouldReturnAt = carbon($this->input('should_return_at'));
            $this->merge(['should_return_at' => $shouldReturnAt]);
        }

        if ($this->has('marked_out_at')) {
            $markedOutAt = carbon($this->input('marked_out_at'));
            $this->merge(['should_out_at' => $markedOutAt]);
        }

        if ($this->has('marked_return_at')) {
            $markedReturnAt = carbon($this->input('marked_return_at'));
            $this->merge(['should_return_at' => $markedReturnAt]);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $time = $this->getCarRegisterTime();
        return Gate::allows('edit-car-register-time', $time);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'should_out_at' => ['date'],
            'should_return_at' => ['date'],

            'marked_out_at' => ['date'],
            'marked_return_at' => ['date'],
        ]);

        return $this->returnRules();
    }
}
