<?php

namespace App\Http\Resources\Car;

use App\Http\Resources\Company\CompanyResource;
use App\Http\Resources\Worklist\WorklistResource;
use App\Traits\ApiCollectionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
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
            'brand' => $this->brand,
            'model' => $this->model,
            'year' => $this->year,
            'car_name' => $this->car_name,
            'car_license' => $this->car_license,
            'insured' => $this->insured,
            'insurance_tax' => $this->insurance_tax,
            'formatted_insurance_tax' => $this->formatted_insurance_tax,
            'status' => $this->status,
            'status_description' => $this->status_description,
            'car_image_url' => $this->car_image_url,
            'apk' => $this->apk
        ];
        if ($this->relationLoaded('company')) {
            $structure['company'] = new CompanyResource($this->company);
        }
        if ($this->relationLoaded('worklists')) {
            $structure['worklists'] = WorklistResource::collection(
                $this->worklists
            );
        }
        if ($this->relationLoaded('registeredTimes')) {
            $regTimes = CarRegisterTimeResource::collection(
                $this->registeredTimes
            );
            $structure['registered_times'] = $regTimes;
        }
        if ($this->relationLoaded('registeredTimeEmployees')) {
            $regTimeEmps = $this->registeredTimeEmployees;
            $regTimeEmps = CarRegisterTimeEmployeeResource::collection(
                $regTimeEmps
            );
            $structure['registered_employees'] = $regTimeEmps;
        }

        return $structure;
    }
}
