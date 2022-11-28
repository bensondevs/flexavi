<?php

namespace App\Http\Requests\Company\Employees;

use App\Rules\NumberIsMultiplyOf;
use App\Traits\CompanyPopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class PopulateEmployeesRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()
            ->fresh()
            ->can('view-any-employee');
    }

    /**
     * The query options for to populate employees
     *
     * @return array
     */
    public function options(): array
    {
        // Prepare relations
        $this->addWith('user');
        // $this->addWith('address');
        // $this->addWithCount('inspections');
        // $this->addWithCount('todayAppointments');

        // Prepare result options
        $this->setPaginationSize();
        $this->setEmployeeTypeFilter();
        $this->setEmployeeStatusFilter();

        return $this->collectCompanyOptions();
    }

    /**
     * Set pagination per page size.
     *
     * Referencing from the design the size should be
     * multiplication of 4.
     *
     * @TODO Settings - Implement pagination size here
     *
     * @return void
     * @see
     */
    private function setPaginationSize(): void
    {
        // TODO: integrate per_page with emplooye default per page pagination setting
        $this->merge([
            'per_page' => (int) $this->get('per_page', 12)
        ]);
    }

    /**
     * Set employee filter by type
     *
     * @return void
     */
    private function setEmployeeTypeFilter(): void
    {
        if ($this->input('type')) {
            $this->addWhere([
                'column' => 'employee_type',
                'operator' => '=',
                'value' => $this->input('type'),
            ]);
        }
    }

    /**
     * Set employee filter by status
     *
     * @return void
     */
    private function setEmployeeStatusFilter(): void
    {
        if ($this->input('status')) {
            $this->addWhere([
                'column' => 'employment_status',
                'operator' => '=',
                'value' => $this->input('status'),
            ]);
        }
        if ($this->input('keyword')) {
            $this->addWhereHas('user', [
                [
                    'column' => 'users.fullname',
                    'operator' => 'LIKE',
                    'value' => '%' . $this->input('keyword') . '%',
                ]
            ]);
        }
    }

    /**
     * Prepare input request before validation
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->setPaginationSize();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'per_page' => [
                'nullable', (new NumberIsMultiplyOf(4))
            ]
        ];
    }
}
