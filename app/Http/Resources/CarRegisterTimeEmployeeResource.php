<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Traits\ApiCollectionResource;

class CarRegisterTimeEmployeeResource extends JsonResource
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
            'car_register_time_id' => $this->car_register_time_id,
            'employee_id' => $this->employee_id,
            'passanger_type' => $this->passanger_type,
            'passanger_type_description' => $this->passanger_type_description,
            'out_time' => $this->out_time,
        ];

        if ($this->relationLoaded('company')) {
            $company = new CompanyResource($this->company);
            $structure['company'] = $company;
        }

        if ($this->relationLoaded('carRegisterTime')) {
            $time = new CarRegisterTimeResource($this->carRegisterTime);
            $structure['car_register_time'] = $time;
        }

        if ($this->relationLoaded('car')) {
            $car = new CarResource($this->car);
            $structure['car'] = $car;
        }

        if ($this->relationLoaded('employee')) {
            $employe = new EmployeeResource($this->employee);
            $structure['employee'] = $employee;
        }

        return $structure;
    }
}