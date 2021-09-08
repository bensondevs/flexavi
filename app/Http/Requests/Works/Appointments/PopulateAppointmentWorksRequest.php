<?php

namespace App\Http\Requests\Works\Appointments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\CompanyPopulateRequestOptions;

use App\Models\Appointment;

use App\Enums\Work\WorkStatus;

class PopulateAppointmentWorksRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    private $appointment;

    public function getAppointment()
    {
        if ($this->appointment) return $this->appointment;

        $id = $this->input('appointment_id');
        return $this->appointment = Appointment::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('view-any-work');
    }

    /**
     * Manipulate received input to be validated.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->has('status')) {
            $status = $this->input('status');
            $status = is_numeric($status) ? 
                $status : ((int) $status);
            $this->merge(['status' => $status]);
        }

        if ($this->has('min_quantity')) {
            $minQuantity = $this->input('min_quantity');
            $minQuantity = is_numeric($minQuantity) ? 
                $minQuantity : ((int) $minQuantity);
            $this->merge(['min_quantity' => $minQuantity]);
        }

        if ($this->has('max_quantity')) {
            $maxQuantity = $this->input('max_quantity');
            $maxQuantity = is_numeric($maxQuantity) ? 
                $maxQuantity : ((int) $maxQuantity);
            $this->merge(['max_quantity' => $maxQuantity]);
        }

        if ($this->has('min_unit_price')) {
            $minUnitPrice = $this->input('min_unit_price');
            $minUnitPrice = is_numeric($minUnitPrice) ? 
                $minUnitPrice : ((double) $minUnitPrice);
            $this->merge(['min_unit_price' => $minUnitPrice]);
        }

        if ($this->has('max_unit_price')) {
            $maxUnitPrice = $this->input('max_unit_price');
            $maxUnitPrice = is_numeric($maxUnitPrice) ?
                $maxUnitPrice : ((double) $maxUnitPrice);
            $this->merge(['max_unit_price' => $maxUnitPrice]);
        }

        if ($this->has('min_total_price')) {
            $minTotalPrice = $this->input('min_total_price');
            $minTotalPrice = is_numeric($minTotalPrice) ?
                $minTotalPrice : ((double) $minTotalPrice);
            $this->merge(['min_total_price' => $minTotalPrice]);
        }

        if ($this->has('max_total_price')) {
            $maxTotalPrice = $this->input('max_total_price');
            $maxTotalPrice = is_numeric($maxTotalPrice) ?
                $maxTotalPrice : ((double) $maxTotalPrice);
            $this->merge(['max_total_price' => $maxTotalPrice]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => [
                'min:' . WorkStatus::Created, 
                'max:' . WorkStatus::Unfinished
            ],
            'min_quantity' => ['numeric'],
            'unit_price' => ['numeric'],
        ];
    }

    public function options()
    {
        if ($status = $this->input('status')) {
            $this->addWhere([
                'column' => 'status',
                'value' => $status,
            ]);
        }

        if ($minQuantity = $this->input('min_quantity')) {
            $this->addWhere([
                'column' => 'quantity',
                'operator' => '>=',
                'value' => $minQuantity,
            ]);
        }

        if ($maxQuantity = $this->input('max_quantity')) {
            $this->addWhere([
                'column' => 'quantity',
                'operator' => '<=',
                'value' => $maxQuantity,
            ]);
        }

        if ($minUnitPrice = $this->input('min_unit_price')) {
            $this->addWhere([
                'column' => 'unit_price',
                'operator' => '>=',
                'value' => $minUnitPrice,
            ]);
        }

        if ($maxUnitPrice = $this->input('max_unit_price')) {
            $this->addWhere([
                'column' => 'unit_price',
                'operator' => '<=',
                'value' => $maxUnitPrice,
            ]);
        }

        if ($minTotalPrice = $this->input('min_total_price')) {
            $this->addWhere([
                'column' => 'total_price',
                'operator' => '>=',
                'value' => $minTotalPrice,
            ]);
        }

        if ($maxTotalPrice = $this->input('max_total_price')) {
            $this->addWhere([
                'column' => 'total_price',
                'operator' => '<=',
                'value' => $maxTotalPrice,
            ]);
        }

        return $this->collectOptions();
    }
}
