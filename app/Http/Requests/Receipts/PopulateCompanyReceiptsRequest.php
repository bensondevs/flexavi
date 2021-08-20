<?php

namespace App\Http\Requests\Receipts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\CompanyPopulateRequestOptions;

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
        return Gate::allows('view-any-receipt');
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

    public function options()
    {
        if ($appointmentId = $this->input('appointment_id')) {
            $this->addWhereHasMorph('receiptable', [\App\Models\Appointment::class], [
                [
                    'column' => 'appointments.id',
                    'operator' => '=',
                    'value' => $appointmentId,
                ]
            ]);
        }

        return $this->collectCompanyOptions();
    }
}
