<?php

namespace App\Http\Requests\Company\Addresses;

use App\Traits\{AddressableRequest, CompanyPopulateRequestOptions, RequestHasRelations};
use Illuminate\Foundation\Http\FormRequest;

class PopulateAddressesRequest extends FormRequest
{
    use AddressableRequest;
    use RequestHasRelations;
    use CompanyPopulateRequestOptions;

    /**
     * List of configurable relationships
     *
     * @var array
     */
    private $relationNames = [
        'with_addressable' => false,
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $addressable = $this->getAddressable();

        return $this->user()
            ->fresh()
            ->can('view-any-address', $addressable);
    }

    /**
     * Get populate query and settings
     *
     * @return array
     */
    public function options()
    {
        return $this->collectOptions();
    }

    /**
     * Get owner options
     *
     * @return array
     */
    public function ownerOptions()
    {
        $owner = $this->getOwner();
        $this->addWhere([
            'column' => 'addressable_type',
            'operator' => '=',
            'value' => get_class($owner),
        ]);
        $this->addWhere([
            'column' => 'addressable_id',
            'operator' => '=',
            'value' => $owner->id,
        ]);

        return $this->collectOptions();
    }

    /**
     * Get employee options
     *
     * @return array
     */
    public function employeeOptions()
    {
        $employee = $this->getEmployee();
        $this->addWhere([
            'column' => 'addressable_type',
            'operator' => '=',
            'value' => get_class($employee),
        ]);
        $this->addWhere([
            'column' => 'addressable_id',
            'operator' => '=',
            'value' => $employee->id,
        ]);

        return $this->collectOptions();
    }

    /**
     * Get company options
     *
     * @return array
     */
    public function companyOptions()
    {
        $company = $this->getCompany();
        $this->addWhere([
            'column' => 'addressable_type',
            'operator' => '=',
            'value' => get_class($company),
        ]);
        $this->addWhere([
            'column' => 'addressable_id',
            'operator' => '=',
            'value' => $company->id,
        ]);

        return $this->collectOptions();
    }

    /**
     * Get customer options
     *
     * @return array
     */
    public function customerOptions()
    {
        $customer = $this->getCustomer();
        $this->addWhere([
            'column' => 'addressable_type',
            'value' => get_class($customer),
        ]);
        $this->addWhere([
            'column' => 'addressable_id',
            'value' => $customer->id,
        ]);

        return $this->collectOptions();
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

    /**
     * Prepare input for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->prepareRelationInputs();
    }
}
