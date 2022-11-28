<?php

namespace App\Http\Requests\Company\Receipts;

use App\Models\Appointment\Appointment;
use App\Traits\CompanyPopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class PopulateCompanyReceiptsRequest extends FormRequest
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
            ->can('view-any-receipt');
    }

    /**
     * Get options
     *
     * @return array
     */
    public function options()
    {
        if ($appointmentId = $this->input('appointment_id')) {
            $this->addWhereHasMorph(
                'receiptable',
                [Appointment::class],
                [
                    [
                        'column' => 'appointments.id',
                        'operator' => '=',
                        'value' => $appointmentId,
                    ],
                ]
            );
        }

        return $this->collectCompanyOptions();
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
