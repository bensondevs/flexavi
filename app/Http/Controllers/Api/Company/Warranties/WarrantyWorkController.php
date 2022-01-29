<?php

namespace App\Http\Controllers\Api\Company\Warranties;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\WarrantyRepository;

class WarrantyWorkController extends Controller
{
    /**
     * Warranty repository class container
     * 
     * @var \App\Repositories\WarrantyRepository|null
     */
    private $warranty;

    /**
     * Controller constructor method
     * 
     * @param  \App\Repositories\WarrantyRepository  $warranty
     * @return void
     */
    public function __construct(WarrantyRepository $warranty)
    {
        $this->warranty = $warranty;
    }

    /**
     * Attach a work to warranty
     * 
     * @param  AttachWorkRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function attach(AttachWorkRequest $request)
    {
        $warranty = $request->getWarranty();
        $this->warranty->setModel($warranty);

        $work = $request->getWork();
        $this->warranty->attachWork($work);

        return apiResponse($this->warranty);
    }

    /**
     * Update the work warranty
     * 
     * @param  UpdateWorkRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function update(UpdateWorkRequest $request)
    {
        $workWarranty = $request->getWorkWarranty();
        $input = $request->validated();

        $this->warranty->updateWorkWarranty($workWarranty, $input);

        return response()->json(['work_warranty' => $workWarranty]);
    }

    /**
     * Detach a work from warranty
     * 
     * @param  DetachWorkRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function detach(DetachWorkRequest $request)
    {
        $workWarranty = $request->getWorkWarranty();
        $this->warranty->deleteWorkWarranty($workWarranty);

        return apiResponse($this->warranty);
    }
}
