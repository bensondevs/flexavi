<?php

namespace App\Http\Resources\Car;

use App\Http\Resources\Worklist\WorklistResource;
use App\Traits\ApiCollectionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarRegisterTimeResource extends JsonResource
{
    use ApiCollectionResource;

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
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
        if ($this->is_out_late) {
            $structure['out_late'] = true;

            $difference = $this->late_out_difference;
            $structure['late_out_difference_minute'] = $difference;
        }
        if ($this->is_return_late) {
            $structure['return_late'] = true;

            $difference = $this->late_return_difference;
            $structure['late_return_difference_minute'] = $difference;
        }
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
