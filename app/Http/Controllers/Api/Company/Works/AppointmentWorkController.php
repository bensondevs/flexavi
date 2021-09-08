<?php

namespace App\Http\Controllers\Api\Company\Works;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Works\AttachWorkRequest as AttachRequest;
use App\Http\Requests\Works\AttachManyWorksRequest as AttachManyRequest;
use App\Http\Requests\Works\DetachWorkRequest as DetachRequest;
use App\Http\Requests\Works\DetachManyWorksRequest as DetachManyRequest;
use App\Http\Requests\Works\TruncateWorksRequest as TruncateRequest;
use App\Http\Requests\Works\Appointments\SaveAppointmentWorkRequest as SaveRequest;
use App\Http\Requests\Works\Appointments\PopulateAppointmentWorksRequest as PopulateRequest;

use App\Http\Resources\WorkResource;

use App\Repositories\WorkRepository;

class AppointmentWorkController extends Controller
{
    /**
     * Repository Container 
     * 
     * @var \App\Repositories\WorkRepository
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
     * @return json
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
     * @return json
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
     * @return json
     */
    /*public function store(SaveRequest $request)
    {
        $input = $request->validated();
        $this->work->save($input);

        $appointment = $request->getAppointment();
        $this->work->attachTo($appointment);

        return apiResponse($this->work);
    }*/

    /**
     * Attach work to appointment
     * 
     * @param AttachRequest $request
     * @return json
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
     * @return json
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
     * @return json
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
     * @return json
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
     * @return json
     */
    public function truncate(TruncateRequest $request)
    {
        $appointment = $request->getAppointment();
        $this->work->truncate($appointment);

        return apiResponse($this->work);
    }
}
