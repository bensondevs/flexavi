<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Works\FindWorkRequest as FindRequest;
use App\Http\Requests\Works\DeleteWorkRequest as DeleteRequest;
use App\Http\Requests\Works\AddAppointmentWorkRequest as AppointmentAdd;
use App\Http\Requests\Works\PopulateContractWorksRequest as ContractPopulateRequest;
use App\Http\Requests\Works\PopulateQuotationWorksRequest as QuotationPopulateRequest;
use App\Http\Requests\Works\PopulateAppointmentWorkersRequest as AppointmentPopulateRequest;

use App\Http\Resources\WorkResource;

use App\Repositories\WorkRepository;

class WorkController extends Controller
{
    private $work;

    public function __construct(WorkRepository $work)
    {
    	$this->work = $work;
    }

    public function quotationWorks(QuotationPopulateRequest $request)
    {
        $options = $request->options();

        $works = $this->work->all($options);
        $works = $this->work->paginate();
        $works = WorkResource::apiCollection($works);

        return response()->json(['works' => $works]);
    }

    public function contractWorks(ContractPopulateRequest $request)
    {
        $options = $request->options();

        $works = $this->work->all($options);
        $works = $this->work->paginate();
        $works = WorkResource::apiCollection($works);

        return response()->json(['works' => $works]);
    }

    public function appointmentWorks(AppointmentPopulateRequest $request)
    {
        $options = $request->options();

        $works = $this->work->all($options);
        $works = $this->work->paginate();
        $works = WorkResource::apiCollection($works);

        return response()->json(['works' => $works]);
    }

    public function addAppointmentWork(AppointmentAddRequest $request)
    {
        $input = $request->onlyInRules();
        $work = $this->work->addToAppointment($input);

        return apiResponse($this->work);
    }

    public function removeAppointmentWork(AppointmentRemoveRequest $request)
    {

    }
}
