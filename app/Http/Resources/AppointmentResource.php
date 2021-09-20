<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Traits\ApiCollectionResource;

use App\Http\Resources\CompanyResource;
use App\Http\Resources\CustomerResource;

use App\Enums\Appointment\AppointmentStatus;

class AppointmentResource extends JsonResource
{
    use ApiCollectionResource;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $structure = [
            'id' => $this->id,

            'customer_id' => $this->customer_id,

            'status' => $this->status,
            'status_description' => $this->status_description,

            'type' => $this->type,
            'type_description' => $this->type_description,

            'status' => $this->status,
            'status_description' => $this->status_description,

            'start' => $this->start,
            'end' => $this->end,
            'include_weekend' => $this->include_weekend,

            'note' => $this->note,
        ];

        if ($this->status >= ((string) AppointmentStatus::Created)) {
            $structure['created_at'] = $this->created_at;
        }

        if ($this->status >= ((string) AppointmentStatus::InProcess)) {
            $structure['in_process_at'] = $this->in_process_at;
        }

        if ($this->status >= ((string) AppointmentStatus::Processed)) {
            $structure['processed_at'] = $this->processed_at;
        }

        if ($this->status >= ((string) AppointmentStatus::Calculated)) {
            $structure['calculated_at'] = $this->calculated_at;
        }

        if ($this->status >= ((string) AppointmentStatus::Cancelled)) {
            $structure['cancelled_at'] = $this->cancelled_at;
            $structure['cancellation_vault'] = $this->cancellation_vault;
            $structure['cancellation_vault_description'] = $this->cancellation_vault_description;
            $structure['cancellation_cause'] = $this->cancellation_cause;
            $structure['cancellation_note'] = $this->cancellation_note;
        }

        if ($this->relationLoaded('customer')) {
            $structure['customer'] = new CustomerResource($this->customer);
        }

        if ($this->relationLoaded('subs')) {
            $structure['subs'] = SubAppointmentResource::collection($this->subs);
        }

        if ($this->relationLoaded('quotation')) {
            $structure['quotation'] = new QuotationResource($this->quotation);
        }

        if ($this->relationLoaded('works')) {
            $structure['works'] = WorkResource::collection($this->works);
        }

        if ($this->relationLoaded('finishedWorks')) {
            $structure['finished_works'] = WorkResource::collection($this->finishedWorks);
        }

        if ($this->relationLoaded('worklists')) {
            $structure['worklists'] = WorklistResource::collection($this->worklists);
        }

        if ($this->relationLoaded('workdays')) {
            $structure['workdays'] = WorkdayResource::collection($this->workdays);
        }

        if ($this->relationLoaded('executeWorks')) {
            $structure['execute_works'] = $this->executeWorks;
        }

        if ($this->relationLoaded('warranty')) {
            $structure['warranty'] = new WarrantyResource($this->warranty);
        }

        /*if ($this->relationLoaded('paymentReminder')) {
            $structure['payment_reminder'] = new PaymentReminderResource($this->paymentReminder);
        }*/

        if ($this->relationLoaded('employees')) {
            $structure['employees'] = EmployeeResource::collection($this->employees);
        }

        if ($this->relationLoaded('costs')) {
            $structure['costs'] = CostResource::collection($this->costs);
        }

        if ($this->relationLoaded('revenues')) {
            $structure['revenues'] = RevenueResource::collection($this->revenues);
        }

        if ($this->relationLoaded('invoice')) {
            $structure['invoice'] = new InvoiceResource($this->invoice);
        }

        if ($this->relationLoaded('calculation')) {
            $structure['calculation'] = new CalculationResource($this->calculation);
        }

        return $structure;
    }
}
