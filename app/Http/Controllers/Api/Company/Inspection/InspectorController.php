<?php

namespace App\Http\Controllers\Api\Company\Inspection;

use App\Http\Controllers\Controller;

class InspectorController extends Controller
{
    private $inspector;

    /*public function __construct(InspectorRepository $inspector)
    {
    	$this->inspector = $inspectorRepository;
    }

    public function companyInspectors(PopulateRequest $request)
    {
    	$inspectors = $this->inspector->all($request->options());
    	$inspectors = $this->inspector->paginate();
    	$inspectors->data = InspectorResource::collection($inspectors);

    	return response()->json(['inspectors' => $inspectors]);
    }

    public function add(SaveRequest $request)
    {
        $input = $request->ruleWithCompany();
    	$inspector = $this->inspector->save($input);

    	return apiResponse($this->inspector, ['inspector' => $inspector]);
    }

    public function remove(RemoveRequest $request)
    {
        $inspector = $request->getInspector();

    	$this->inspector->setModel($inspector);
    	$this->inspector->delete();

    	return apiResponse($this->inspector);
    }*/
}
