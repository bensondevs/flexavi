<?php

namespace App\Http\Controllers\Api\Company\Warranty;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Warranties\FindWarrantyRequest;
use App\Http\Resources\Warranty\WarrantyWorkResource;
use App\Models\Warranty\WarrantyWork;
use App\Repositories\Warranty\WarrantyWorkRepository;

class WarrantyWorkController extends Controller
{
    /**
     * Warranty repository class container
     *
     * @var WarrantyWorkRepository|null
     */
    private $warrantyWork;

    /**
     * Controller constructor method
     *
     * @param WarrantyWorkRepository $warrantyWork
     * @return void
     */
    public function __construct(WarrantyWorkRepository $warrantyWork)
    {
        $this->warrantyWork = $warrantyWork;
    }

    /**
     * Populate warranty works
     *
     * @param FindWarrantyRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function warrantyWorks(FindWarrantyRequest $request)
    {
        $warrantyWorks = WarrantyWork::with(['work'])
            ->where('warranty_id', $request->warranty_id)
            ->get();
        $warrantyWorks = WarrantyWorkResource::collection($warrantyWorks);

        return response()->json(['warranty_works' => $warrantyWorks]);
    }

    /**
     * Attach a work to warranty
     *
     * @param AttachWorkRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function attach(AttachWorkRequest $request)
    {
        $warranty = $request->getWarranty();
        $input = $request->validated();

        $this->warrantyWork->attach($warranty, $input);

        return apiResponse($this->warranty);
    }

    /**
     * Attach many works to warranty
     *
     * @param AttachManyWorkRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function attachMany(AttachManyWorkRequest $request)
    {
        $warranty = $request->getWarranty();
        $input = $request->validated();

        $this->warrantyWork->multipleAttach($warranty, $input);

        return apiResponse($this->warranty);
    }

    /**
     * Update the work warranty
     *
     * @param UpdateWorkRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function update(UpdateWorkRequest $request)
    {
        $workWarranty = $request->getWorkWarranty();
        $input = $request->validated();

        $this->warranty->update($workWarranty, $input);

        return response()->json(['work_warranty' => $workWarranty]);
    }

    /**
     * Detach a work from warranty
     *
     * @param DetachWorkRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function detach(DetachWorkRequest $request)
    {
        $warrantyWork = $request->getWarrantyWork();

        $this->warrantyWork->setModel($warrantyWork);
        $this->warranty->detach();

        return apiResponse($this->warranty);
    }
}
