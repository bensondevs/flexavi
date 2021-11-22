<?php

namespace App\Http\Controllers\Api\Company\Works;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Works\{
    AttachWorkRequest as AttachRequest,
    AttachManyWorksRequest as AttachManyRequest,
    DetachWorkRequest as DetachRequest,
    DetachManyWorksRequest as DetachManyRequest,
    TruncateWorksRequest as TruncateRequest
};

use App\Http\Resources\WorkResource;

use App\Repositories\WorkRepository;

class SubAppointmentWorkController extends Controller
{
    /**
     * Work Repository Class Container
     * 
     * @var \App\Repositories\WorkRepository
     */
    private $work;

    /**
     * Controller constructor method
     * 
     * @param \App\Repsitories\WorkRepository  $work
     * @return void
     */
    public function __construct(WorkRepository $work)
    {
        $this->work = $work;
    }

    /**
     * Populate sub appointment works
     * 
     * @param PopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function subAppointmentWorks(PopulateRequest $request)
    {
        $subAppointment = $request->getSubAppointment();
        $options = $request->options();

        $works = $this->work->subAppointmentWorks($subAppointment, $options, true);
        $works = WorkResource::apiCollection($works);

        return response()->json(['works' => $works]);
    }

    /**
     * Store work and attach to sub appointment
     * 
     * @param SaveRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function store(SaveRequest $request)
    {
        $input = $request->validated();
        $this->work->save();

        $subAppointment = $request->getSubAppointment();
        $this->work->attachTo($subAppointment);

        return apiResponse($this->work);
    }

    /**
     * Attach work to sub appointment
     * 
     * @param AttachRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function attach(AttachRequest $request)
    {
        $work = $request->getWork();
        $this->work->setModel($work);

        $subAppointment = $request->getSubAppointment();
        $this->work->attachTo($subAppointment);

        return apiResponse($this->work);
    }

    /**
     * Attach many works to sub appointment
     * 
     * @param AttachManyRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function attachMany(AttachManyRequest $request)
    {
        $subAppointment = $request->getSubAppointment();
        $workIds = $request->input('work_ids');

        $this->work->attachToMany($subAppointment, $workIds);

        return apiResponse($this->work);
    }

    /**
     * Detach work from sub appointment
     * 
     * @param DetachRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function detach(DetachRequest $request)
    {
        $work = $request->getWork();
        $this->work->setModel($work);

        $subAppointment = $request->getSubAppointment();
        $this->work->detachFrom($subAppointment);

        return apiResponse($this->work);
    }

    /**
     * Detach many works from sub appointment
     * 
     * @param DetachManyRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function detachMany(DetachManyRequest $request)
    {
        $subAppointment = $request->getSubAppointment();
        $workIds = $request->input('work_ids');

        $this->work->detachManyFrom($subAppointment, $works);

        return apiResponse($this->work);
    }

    /**
     * Delete all works from sub appointment
     * 
     * @param TruncateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function truncate(TruncateRequest $request)
    {
        $subAppointment = $request->getSubAppointment();
        $this->work->truncate($subAppointment);
        return apiResponse($this->work);
    }
}