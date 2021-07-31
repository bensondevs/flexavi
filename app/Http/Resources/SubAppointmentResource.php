<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Traits\ApiCollectionResource;

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

        if ($this->status == 'cancelled') {
            $structure = array_merge($structure, [
                'cancellation_cause' => $this->cancellation_cause,
                'cancellation_vault' => $this->cancellation_vault,
                'cancellation_vault_description' => $this->cancellation_vault_description,
                'cancellation_note' => $this->cancellation_note,
            ]);
        }

        return $structure;
    }
}
