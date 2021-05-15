<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\PopulateInspectorsRequest;

use App\Models\Inspector;

use App\Repositories\InspectorRepository;

class InspectorController extends Controller
{
    private $inspector;

    public function __construct(
    	InspectorRepository $inspector
    )
    {
    	$this->inspector = $inspectorRepository;
    }

    public function inspectors(PopulateInspectorsRequest $request)
    {
    	$inspectors = $this->inspector->all($request->options());
    	$inspectors = $this->inspector->paginate();
    	$inspectors->data = InspectorResource::collection($inspectors);

    	return response()->json(['inspectors' => $inspectors]);
    }

    public function add(SaveInspectorRequest $request)
    {
    	$inspector = $this->inspector->save(
    		$request->onlyInRules()
    	);

    	return apiResponse($this->inspector, $inspector);
    }

    public function remove(Request $request)
    {
    	$this->inspector->find($request->input('id'));
    	$this->inspector->delete();

    	return apiResponse($this->inspector);
    }
}
