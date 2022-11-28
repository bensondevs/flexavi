<?php

namespace App\Http\Resources\Worklist;

use App\Http\Resources\Car\CarResource;
use App\Http\Resources\Employee\EmployeeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class WorklistCarResource extends JsonResource
{
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
            'worklist_id' => $this->worklist_id,
            'car_id' => $this->car_id,
            'employee_in_charge_id' => $this->employee_in_charge_id,
            'should_return_at' => $this->should_return_at,
            'returned_at' => $this->returned_at,
        ];

        if ($this->relationLoaded('worklist')) {
            $structure['worklist'] = new WorklistResource($this->worklist);
        }

        if ($this->relationLoaded('car')) {
            $structure['car'] = new CarResource($this->car);
        }

        if ($this->relationLoaded('employeeInCharge')) {
            $structure['employee_in_charge'] = new EmployeeResource($this->employeeInCharge);
        }

        return $structure;
    }
}
