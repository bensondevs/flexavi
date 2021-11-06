<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Traits\ApiCollectionResource;

class CarRegisterTimeResource extends JsonResource
{
    use ApiCollectionResource;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $structure = [
            'id' => $this->id,
            'worklist_id' => $this->worklist_id,
            'car_id' => $this->car_id,

            'should_out_at' => $this->should_out_at,
            'should_return_at' => $this->should_return_at,

            'marked_out_at' => $this->marked_out_at,
            'marked_return_at' => $this->marked_return_at,
        ];

        if ($this->relationLoaded('worklist')) {
            $worklist = new WorklistResource($this->worklist);
            $structure['worklist'] = $worklist;
        }

        if ($this->relationLoaded('car')) {
            $car = new CarResource($this->car);
            $structure['car'] = $car;
        }

        return $structure;
    }
}
