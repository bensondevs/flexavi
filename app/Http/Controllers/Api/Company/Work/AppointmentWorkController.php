<?php

namespace App\Http\Controllers\Api\Company\Work;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Works\{Appointments\PopulateAppointmentWorksRequest as PopulateRequest};
use App\Http\Requests\Company\Works\Appointments\SaveAppointmentWorkRequest as SaveRequest;
use App\Http\Requests\Company\Works\AttachManyWorksRequest as AttachManyRequest;
use App\Http\Requests\Company\Works\AttachWorkRequest as AttachRequest;
use App\Http\Requests\Company\Works\DetachManyWorksRequest as DetachManyRequest;
use App\Http\Requests\Company\Works\DetachWorkRequest as DetachRequest;
use App\Http\Requests\Company\Works\TruncateWorksRequest as TruncateRequest;
use App\Http\Resources\Work\WorkResource;
use App\Repositories\Work\WorkRepository;

class AppointmentWorkController extends Controller
{
    /**
     * Repository Container
     *
     * @var WorkRepository
     */
    private $work;

    /**
     * Create New Controller Instance
     *
     * @return void
     */
    public function __construct(WorkRepository $work)
    {
        $this->work = $work;
    }

    /**
     * Populate works that attached within appointment
     *
     * @param PopulateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function appointmentWorks(PopulateRequest $request)
    {
        $appointment = $request->getAppointment();
        $options = $request->options();

        $works = $this->work->appointmentWorks($appointment, $options, true);
        $works = WorkResource::apiCollection($works);

        return response()->json(['works' => $works]);
    }

    /**
     * Populate works that finished at certain appointment.
     *
     * @param PopulateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function appointmentFinishedWorks(PopoulateFinishedRequest $request)
    {
        $options = $request->options();

        $works = $this->work->all($options, true);
        $works = WorkResource::apiCollection($works);

        return response()->json(['works' => $works]);
    }

    /**
     * Store work and directly attach it to appointment
     *
     * @param SaveRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function store(SaveRequest $request)
    {
        $input = $request->validated();
        $this->work->save($input);

        $appointment = $request->getAppointment();
        $this->work->attachTo($appointment);

        return apiResponse($this->work);
    }

    /**
     * Attach work to appointment
     *
     * @param AttachRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function attach(AttachRequest $request)
    {
        $work = $request->getWork();
        $this->work->setModel($work);

        $appointment = $request->getAppointment();
        $this->work->attachTo($appointment);

        return apiResponse($this->work);
    }

    /**
     * Attach many works to appointment
     *
     * @param AttachManyRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function attachMany(AttachManyRequest $request)
    {
        $appointment = $request->getAppointment();
        $workIds = $request->input('work_ids');

        $this->work->attachToMany($appointment, $workIds);

        return apiResponse($this->work);
    }

    /**
     * Detach work from appointment
     *
     * @param DetachRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function detach(DetachRequest $request)
    {
        $work = $request->getWork();
        $this->work->setModel($work);

        $appointment = $request->getAppointment();
        $this->work->detachFrom($appointment);

        return apiResponse($this->work);
    }

    /**
     * Detach many works from appointment
     *
     * @param DetachManyRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function detachMany(DetachManyRequest $request)
    {
        $appointment = $request->getAppointment();
        $workIds = $request->input('work_ids');

        $this->work->detachManyFrom($appointment, $workIds);

        return apiResponse($this->work);
    }

    /**
     * Truncate works inside appointment
     *
     * @param TruncateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function truncate(TruncateRequest $request)
    {
        $appointment = $request->getAppointment();
        $this->work->truncate($appointment);

        return apiResponse($this->work);
    }
}
