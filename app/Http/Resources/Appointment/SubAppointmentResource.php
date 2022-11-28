<?php

namespace App\Http\Resources\Appointment;

use App\Enums\SubAppointment\SubAppointmentStatus;
use App\Traits\ApiCollectionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SubAppointmentResource extends JsonResource
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
            'start' => $this->start,
            'end' => $this->end,
            'note' => $this->note,
        ];

        if ($this->status == SubAppointmentStatus::Cancelled) {
            $structure = array_merge($structure, [
                'cancellation_cause' => $this->cancellation_cause,
                'cancellation_vault' => $this->cancellation_vault,
                'cancellation_vault_description' => $this->cancellation_vault_description,
                'cancellation_note' => $this->cancellation_note,
                'cancelled_at' => $this->cancelled_at,
            ]);
        }

        if ($this->status >= SubAppointmentStatus::Created) {
            $structure['created_at'] = $this->created_at;
        }

        if ($this->status >= SubAppointmentStatus::InProcess) {
            $structure['in_process_at'] = $this->in_process_at;
        }

        if ($this->status >= SubAppointmentStatus::Processed) {
            $structure['processed_at'] = $this->processed_at;
        }

        if ($this->status >= SubAppointmentStatus::Cancelled) {
            $structure['cancelled_at'] = $this->cancelled_at;
        }

        return $structure;
    }
}
