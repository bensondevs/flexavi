<?php

namespace App\Http\Requests\Company\WorkConditionPhotos;

use App\Models\Work\Work;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;

class SaveWorkConditionPhotoRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Work object
     *
     * @var Work|null
     */
    private $work;

    /**
     * Get WorkCondition based on supplied input
     *
     * @return mixed
     */
    public function getCondition()
    {
        // TODO: complete getCondition logic
        // return $this->condition =
        //     $this->condition ?: WorkCondition::findOrFail($this->input('id'));
        return null;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('work condition photos');
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

    /**
     * Get photo data
     *
     * @return array
     */
    public function photoData()
    {
        $data = $this->onlyInRules();
        unset($data['condition_photo']);
        $data['uploader_id'] = $this->user()->id;
        $data['work_id'] = $this->getWork()->id;

        return $data;
    }

    /**
     * Get Work based onsupplied input
     *
     * @return Work
     */
    public function getWork()
    {
        return $this->work =
            $this->work ?: Work::findOrFail($this->input('work_id'));
    }
}
