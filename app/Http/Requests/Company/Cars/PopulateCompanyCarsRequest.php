<?php

namespace App\Http\Requests\Company\Cars;

use App\Traits\CompanyPopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class PopulateCompanyCarsRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()
            ->fresh()
            ->can('view any cars');
    }

    /**
     * Define request options
     *
     * @return array
     */
    public function options()
    {
        $this->setCarStatusFilter();

        return $this->collectCompanyOptions();
    }

    /**
     * Set car filter by status
     *
     * @return void
     */
    private function setCarStatusFilter()
    {
        if ($this->input('status')) {
            $this->addWhere([
                'column' => 'status',
                'operator' => '=',
                'value' => $this->input('status'),
            ]);
        }
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
