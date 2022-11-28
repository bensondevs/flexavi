<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Pricings\FindPricingRequest as FindRequest;
use App\Http\Requests\Admin\Pricings\PopulatePricingsRequest as PopulateRequest;
use App\Http\Requests\Admin\Pricings\SavePricingRequest as SaveRequest;
use App\Repositories\Pricing\PricingRepository;

class PricingController extends Controller
{
    private $pricing;

    public function __construct(PricingRepository $pricing)
    {
        $this->pricing = $pricing;
    }

    public function pricings(PopulateRequest $request)
    {
        $options = $request->options();

        $pricings = $this->pricing->all($options);
        $pricings = $this->pricing->paginate();
        // $pricings->data = PricingRepository::collection($pricings);

        return response()->json(['pricings' => $pricings]);
    }

    public function store(SaveRequest $request)
    {
        $input = $request->onlyInRules();
        $pricing = $this->pricing->save($input);

        return apiResponse($this->pricing, ['pricing' => $pricing]);
    }

    public function update(SaveRequest $request)
    {
        $pricing = $request->getPricing();
        $this->pricing->setModel($pricing);

        $input = $request->onlyInRules();
        $this->pricing->save($input);

        return apiResponse($this->pricing, ['pricing' => $pricing]);
    }

    public function delete(FindRequest $request)
    {
        $pricing = $request->getPricing();

        $this->pricing->setModel($pricing);
        $this->pricing->delete();

        return apiResponse($this->pricing);
    }
}
