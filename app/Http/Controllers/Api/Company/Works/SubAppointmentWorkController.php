<?php

namespace App\Http\Controllers\Api\Company\Works;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Works\AttachWorkRequest as AttachRequest;
use App\Http\Requests\Works\AttachManyWorksRequest as AttachManyRequest;
use App\Http\Requests\Works\DetachWorkRequest as DetachRequest;
use App\Http\Requests\Works\DetachManyWorksRequest as DetachManyRequest;
use App\Http\Requests\Works\TruncateWorksRequest as TruncateRequest;

use App\Http\Resources\WorkResource;

use App\Repositories\WorkRepository;

class SubAppointmentWorkController extends Controller
{
    private $work;

    public function __construct(WorkRepository $work)
    {
        $this->work = $work;
    }

    public function subAppointmentWorks(PopulateRequest $request)
    {
        $subAppointment = $request->getSubAppointment();
        $options = $request->options();

        $works = $this->work->subAppointmentWorks($subAppointment, $options, true);
        $works = WorkResource::apiCollection($works);

        return response()->json(['works' => $works]);
    }

    public function store(SaveRequest $request)
    {
        $input = $request->validated();
        $this->work->save();

        $subAppointment = $request->getSubAppointment();
        $this->work->attachTo($subAppointment);

        return apiResponse($this->work);
    }

    public function attach(AttachRequest $request)
    {
        $work = $request->getWork();
        $this->work->setModel($work);

        $subAppointment = $request->getSubAppointment();
        $this->work->attachTo($subAppointment);

        return apiResponse($this->work);
    }

    public function attachMany(AttachManyRequest $request)
    {
        $subAppointment = $request->getSubAppointment();
        $workIds = $request->input('work_ids');

        $this->work->attachToMany($subAppointment, $workIds);

        return apiResponse($this->work);
    }

    public function detach(DetachRequest $request)
    {
        $work = $request->getWork();
        $this->work->setModel($work);

        $subAppointment = $request->getSubAppointment();
        $this->work->detachFrom($subAppointment);

        return apiResponse($this->work);
    }

    public function detachMany(DetachManyRequest $request)
    {
        $subAppointment = $request->getSubAppointment();
        $workIds = $request->input('work_ids');

        $this->work->detachManyFrom($subAppointment, $works);

        return apiResponse($this->work);
    }

    public function truncate()
    {
        $subAppointment = $request->getSubAppointment();
        $this->work->truncate($subAppointment);

        return apiResponse($this->work);
    }
}