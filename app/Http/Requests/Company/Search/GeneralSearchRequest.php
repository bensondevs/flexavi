<?php

namespace App\Http\Requests\Company\Search;

use App\Services\Algolia\AlgoliaService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Str;

class GeneralSearchRequest extends FormRequest
{
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
        $searchableModels = AlgoliaService::searchableModels();

        return [
            "keyword" => ["nullable", "string"],
            "model_ins" => ["nullable", "array"],
            "model_ins.*" => ["nullable", "string", Rule::in(
                $this->has("model_ins") ? $searchableModels : []
            )],
        ];
    }

    /**
     * Prepare input requests before validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->has("model_ins")) :
            $this->merge([
                "model_ins" => array_map(
                    fn($model) => "App\\Models\\" . ucfirst(str_camel_case(Str::afterLast($model, "."))),
                    explode(",", $this->get("model_ins"))
                )
            ]);
        endif;
    }
}
