<?php

namespace App\Traits;

use App\Models\{ Cost, Workday, Worklist, Appointment };

trait CostableRequest 
{
    /**
     * Found cost model container
     * 
     * @return \App\Models\Cost|null
     */
    private $cost;

    /**
     * Found costable model container
     * 
     * @var mixed
     */
    private $costable;
    
    /**
     * Found workday as costable model container
     * 
     * @var \App\Models\Workday|null
     */
    private $workday;

    /**
     * Found worklist as costable model container
     * 
     * @var \App\Models\Worklist|null
     */
    private $worklist;

    /**
     * Found appointment as costable model container
     * 
     * @var \App\Models\Appointment|null
     */
    private $appointment;

    /**
     * Get cost from the supplied input of "cost_id"
     * 
     * @return \App\Models\Cost|abort 404
     */
    public function getCost()
    {
        if ($this->cost) return $this->cost;

        $id = $this->input('cost_id');
        return $this->cost = Cost::findOrFail($id);
    }

    public function getCostable()
    {
        if ($this->costable) return $this->costable;

        switch (true) {
            /**
             * Primitive costable request
             */
            case ($this->has('costable_type') and $this->has('costable_id')):
                $model = $this->input('costable_type');
                $id = $this->input('costable_id');
                $this->costable = (new $model())->findOrFail($id);
                break;

            /**
             * Costable appointment request
             */
            case $this->has('appointment_id'):
                $this->costable = $this->getAppointment();
                break;

            /**
             * Costable worklist request
             */
            case $this->has('worklist_id'):
                $this->costable = $this->getWorklist();
                break;

            /**
             * Costable workday request
             */
            case $this->has('workday_id'):
                $this->costable = $this->getWorkday();
                break;
            
            /**
             * Defaultly, abort 404 due to no costable selected
             */
            default:
                abort(404, 'No cost recordable type that has been loaded.');
                break;
        }

        return $this->costable;
    }

    /**
     * Get worklist from supplied input of `worklist_id`
     * 
     * @return \App\Models\Worklist
     */
    public function getWorklist()
    {
        if ($this->worklist) return $this->worklist;

        $id = $this->input('worklist_id');
        return $this->worklist = Worklist::findOrFail($id);
    }

    /**
     * Get workday from supplied input of `workday_id`
     * 
     * @return \App\Models\Workday
     */
    public function getWorkday()
    {
        if ($this->workday) return $this->workday;

        $id = $this->input('workday_id');
        return $this->workday = Workday::findOrFail($id);
    }

    /**
     * Get appointment from supplied input of `appointment_id`
     * 
     * @return \App\Models\Appointment
     */
    public function getAppointment()
    {
        if ($this->appointment) return $this->appointment;

        $id = $this->input('appointment_id');
        return $this->appointment = Appointment::findOrFail($id);
    }
}