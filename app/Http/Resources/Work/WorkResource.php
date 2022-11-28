<?php

namespace App\Http\Resources\Work;

use App\Enums\Work\WorkStatus;
use App\Http\Resources\Appointment\AppointmentResource;
use App\Http\Resources\ExecuteWork\ExecuteWorkResource;
use App\Http\Resources\Quotation\QuotationResource;
use App\Http\Resources\Revenue\RevenueResource;
use App\Http\Resources\WorkService\WorkServiceResource;
use App\Traits\ApiCollectionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkResource extends JsonResource
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

            'status' => $this->status,
            'status_description' => $this->status_description,

            'quantity' => $this->quantity,
            'work_service_id' => $this->work_service_id,
            'quantity_unit' => $this->quantity_unit,
            'quantity_with_unit' => $this->quantity . ' ' . $this->quantity_unit,

            'description' => $this->description,
            'unit_price' => $this->unit_price,
            'formatted_unit_price' => $this->formatted_unit_price,

            'total_price' => $this->total_price,
            'formatted_total_price' => $this->formatted_total_price,

            'total_paid' => $this->total_paid,
            'formatted_total_paid' => $this->formatted_total_paid,
        ];

        if ($this->status >= WorkStatus::Created) {
            $structure['created_at'] = $this->created_at;
        }

        if ($this->status >= WorkStatus::InProcess) {
            $structure['executed_at'] = $this->executed_at;
        }

        if ($this->status >= WorkStatus::Finished) {
            $structure['finished_at_appointment_id'] = $this->finished_at_appointment_id;
            $structure['finished_at'] = $this->finished_at;
            $structure['finish_note'] = $this->finish_note;
        }

        if ($this->status >= WorkStatus::Unfinished) {
            $structure['unfinished_at'] = $this->unfinished_at;
        }

        if ($this->relationLoaded('appointments')) {
            $structure['appointments'] = AppointmentResource::collection($this->appointments);
        }

        if ($this->relationLoaded('workService')) {
            $structure['work_service'] = new WorkServiceResource($this->workService);
        }

        if ($this->relationLoaded('finishedAtAppointment') && $this->status >= WorkStatus::Finished) {
            $structure['finished_at_appointment'] = new AppointmentResource($this->finishedAtAppointment);
        }

        if ($this->relationLoaded('quotations')) {
            $structure['quotation'] = new QuotationResource($this->quotation);
        }

        if ($this->relationLoaded('executeWorks')) {
            $structure['execute_works'] = ExecuteWorkResource::collection($this->executeWorks);
        }

        if ($this->relationLoaded('currentExecuteWork')) {
            $structure['current_execute_work'] = new ExecuteWorkResource($this->currentExecuteWork);
        }

        if ($this->relationLoaded('revenueable')) {
            $structure['revenue'] = new RevenueResource($this->revenueable->revenue);
        }

        return $structure;
    }
}
