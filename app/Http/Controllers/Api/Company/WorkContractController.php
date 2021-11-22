<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\WorkContractResource;

use App\Http\Requests\WorkContracts\{
    FindWorkContractRequest as FindRequest,
    SaveWorkContractRequest as SaveRequest,
    PopulateCompanyWorkContractsRequest as PopulateRequest
};

use App\Repositories\WorkContractRepository;

class WorkContractController extends Controller
{
    /**
     * Work Contract Repository class
     * 
     * @var \App\Repositories\WorkContractRepository
     */
    private $contract;

    /**
     * Controller constructor method
     * 
     * @param \App\Repositories\WorkContractRepository  $contract
     * @return void
     */
    public function __construct(WorkContractRepository $contract)
    {
    	$this->contract = $contract;
    }

    /**
     * Populate company work contracts
     * 
     * @param PopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function companyWorkContracts(PopulateRequest $request)
    {
    	$contracts = $this->contract->all($request->options());
    	$contracts = $this->contract->paginate();
    	$contracts->data = WorkContractResource::collection($contracts);

    	return response()->json(['contracts' => $contracts]);
    }

    /**
     * Store company work contract
     * 
     * @param SaveRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function store(SaveRequest $request)
    {
    	// Upload the file
    	$pdfUpload = $request->file('contract_pdf');
    	$contract = $this->contract->uploadContractPdf($pdfUpload);

    	// Save input
    	$input = $request->contractData();
    	$contract = $this->contract->save($input);

    	return apiResponse($this->contract, ['contract' => $contract]);
    }

    /**
     * Update company work contract
     * 
     * @param SaveRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function update(SaveRequest $request)
    {
        $contract = $request->getWorkContract();
        $contract = $this->contract->setModel($contract);

        // Upload the file if exists
        if ($request->hasFile('contract_pdf')) {
            $pdfUpload = $request->file('contract_pdf');
            $contract = $this->contract->uploadContractPdf($pdfUpload);
        }

        $input = $request->contractData();
        $contract = $this->contract->save($input);

        return apiResponse($this->contract, ['contract' => $contract]);
    }

    /**
     * Delete company work contract
     * 
     * @param FindRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function delete(FindRequest $request)
    {
        $contract = $request->getWorkContract();
        $this->contract->setModel($contract);
        $this->contract->delete();

        return apiResponse($this->contract);
    }
}
