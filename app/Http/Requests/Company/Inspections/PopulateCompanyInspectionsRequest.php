<?php

namespace App\Http\Requests\Company\Inspections;

use App\Traits\CompanyPopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class PopulateCompanyInspectionsRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->fresh()->can('view-any-inspection');
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

    /**
     * Get options
     *
     * @return array
     */
    public function options()
    {
        if (strtobool($this->input('with_appointment'))) {
            $this->addWith('appointment.customer');
        }

        if (strtobool($this->input('with_pictures'))) {
            $this->addWith('pictures.works');
        }

        if (strtobool($this->input('with_customer'))) {
            $this->addWith('customer');
        }


        return $this->collectCompanyOptions();
    }
}
