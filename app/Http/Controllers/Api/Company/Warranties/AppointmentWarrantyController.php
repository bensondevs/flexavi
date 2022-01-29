<?php

namespace App\Http\Controllers\Api\Company\Warranties;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppointmentWarrantyController extends Controller
{
    /**
     * Warranty repository class container
     * 
     * @var \App\Repositories\WarrantyRepository|null
     */
    private $warranty;

    /**
     * Appointment repository class container
     * 
     * @var \App\Repositories\AppointmentRepository|null
     */
    private $appointment;

    /**
     * Select work to be warranted and create appointment for it
     * 
     * @param  CreateAppointmentWarrantyRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function select()
    {
        //
    }

    /**
     * Unselect warranty from appointment warranties
     * 
     * @param  RemoveAppointmentWarrantyRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function unselect()
    {
        //
    }

    /**
     * Select unfinshed warranties to be continued next time
     * 
     * @param  SelectUnfinishedWarrantyRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function selectUnfinished()
    {
        //
    }
}
