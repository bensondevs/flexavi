<?php

namespace App\Repositories\Appointment;

use App\Models\{Appointment\Appointment, Cost\Cost};
use App\Repositories\Base\BaseRepository;

class AppointmentCostRepository extends BaseRepository
{
    /**
     * Repository method constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new Cost());
    }

    /**
     * Calculate appointment cost total
     *
     * @param  Appointment $appointment
     * @return float
     */
    public function calculateTotal(Appointment $appointment)
    {
        $costs = $appointment->costs;
        foreach ($costs as $cost) {
            $cost->unpaid_cost = $cost->unpaid_cost;
        }

        return $costs->sum('unpaid_cost');
    }
}
