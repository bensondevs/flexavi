<?php

namespace App\Http\Requests\WorkConditionPhotos;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\CompanyInputRequest;

use App\Models\WorkCondition;

class SaveWorkConditionPhotoRequest extends FormRequest
{
    use CompanyInputRequest;

    private $work;
    private $condition;

    public function getWork()
    {
        return $this->work = $this->work ?:
            Work::findOrFail($this->input('work_id'));
    }

    public function getCondition()
    {
        return $this->condition = $this->condition ?: 
            WorkCondition::findOrFail($this->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->authorizeCompanyAction('work condition photos');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'photo_type' => ['required', 'string'],
            'condition_photo' => ['file', 'mime:jpg,jpeg,svg,png'],
            'photo_description' => ['required', 'string'],
        ]);

        if ($this->isMethod('POST')) {
            $conditionPhotoRule = $this->rules['condition_photo'];
            array_push($conditionPhotoRule, 'required');

            $this->addRule('condition_photo', $conditionPhotoRule);
        }

        return $this->returnRules();
    }

    public function photoData()
    {
        $data = $this->onlyInRules();
        
        // Unset file
        unset($data['condition_photo']);
        
        // Set default data
        $data['uploader_id'] = $this->user()->id;
        $data['work_id'] = $this->getWork()->id;

        return $data;
    }
}
