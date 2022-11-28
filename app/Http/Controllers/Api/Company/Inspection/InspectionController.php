<?php

namespace App\Http\Controllers\Api\Company\Inspection;

use App\Http\Controllers\Api\Company\Illuminate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Inspections\{PopulateCustomerInspectionsRequest as CustomerPopulateRequest};
use App\Http\Requests\Company\Inspections\DeleteInspectionRequest as DeleteRequest;
use App\Http\Requests\Company\Inspections\FindInspectionRequest as FindRequest;
use App\Http\Requests\Company\Inspections\PopulateCompanyInspectionsRequest as CompanyPopulateRequest;
use App\Http\Requests\Company\Inspections\PopulateEmployeeInspectionsRequest as EmployeePopulateRequest;
use App\Http\Requests\Company\Inspections\RestoreInspectionRequest as RestoreRequest;
use App\Http\Requests\Company\Inspections\StoreInspectionRequest as StoreRequest;
use App\Http\Resources\Inspection\InspectionResource;
use App\Repositories\Inspection\InspectionPictureRepository;
use App\Repositories\Inspection\InspectionRepository;
use DB;

class InspectionController extends Controller
{
    /**
     * Inspection Repository Class Container
     *
     * @var InspectionRepository
     */
    private $inspection;

    /**
     * Inspection picture Repository Class Container
     *
     * @var InspectionPictureRepository
     */
    private $inspectionPicture;

    /**
     * Controller constructor method
     *
     * @param InspectionRepository $inspection
     * @param InspectionPictureRepository $inspectionPicture
     * @return void
     */
    public function __construct(
        InspectionRepository        $inspection,
        InspectionPictureRepository $inspectionPicture
    )
    {
        $this->inspection = $inspection;
        $this->inspectionPicture = $inspectionPicture;
    }

    /**
     * Populate company inspections
     *
     * @param CompanyPopulateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function companyInspections(CompanyPopulateRequest $request)
    {
        $options = $request->options();

        $inspections = $this->inspection->all($options);
        $inspections = $this->inspection->paginate();
        $inspections = InspectionResource::apiCollection($inspections);

        return response()->json(['inspections' => $inspections]);
    }

    /**
     * Populate customer inspections
     *
     * @param CustomerPopulateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function customerInspections(CustomerPopulateRequest $request)
    {
        $options = $request->options();

        $inspections = $this->inspection->all($options);
        $inspections = $this->inspection->paginate();
        $inspections = InspectionResource::apiCollection($inspections);

        return response()->json(['inspections' => $inspections]);
    }

    /**
     * Populate employee inspections
     *
     * @param EmployeePopulateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function employeeInspections(EmployeePopulateRequest $request)
    {
        $inspections = $this->inspection->all($request->options(), true);
        $inspections = InspectionResource::apiCollection($inspections);

        return response()->json(['inspections' => $inspections]);
    }

    /**
     * Populate trashed inspections
     *
     * @param CompanyPopulateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function trashedInspections(CompanyPopulateRequest $request)
    {
        $options = $request->options();

        $inspections = $this->inspection->trasheds($options);
        $inspections = $this->inspection->paginate();
        $inspections = InspectionResource::apiCollection($inspections);

        return response()->json(['inspections' => $inspections]);
    }

    /**
     * Store company inspection
     *
     * @param StoreRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        $inspection = $this->inspection->save($request->inspectionData());
        $this->inspectionPicture->save($request->inspectionPictureData(), $inspection);
        DB::commit();
        return apiResponse($this->inspection);
    }

    /**
     * View company inspection
     *
     * @param FindRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function view(FindRequest $request)
    {
        $inspection = $request->getInspection();
        $inspection = new InspectionResource($inspection);

        return response()->json(['inspection' => $inspection]);
    }

    /**
     * Delete inspection
     *
     * @param DeleteRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function delete(DeleteRequest $request)
    {
        $inspection = $request->getInspection();
        $this->inspection->setModel($inspection);

        $force = $request->input('force');
        $this->inspection->delete($force);

        return apiResponse($this->inspection);
    }

    /**
     * Restore inspection
     *
     * @param RestoreRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function restore(RestoreRequest $request)
    {
        $inspection = $request->getInspection();

        $this->inspection->setModel($inspection);
        $inspection = $this->inspection->restore();

        return apiResponse($this->inspection, ['inspection' => $inspection]);
    }
}
