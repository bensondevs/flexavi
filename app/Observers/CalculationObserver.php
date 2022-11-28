<?php

namespace App\Observers;

use App\Models\Appointment\Appointment;
use App\Models\Calculation\Calculation;

class CalculationObserver
{
    /**
     * Handle the Calculation "created" event.
     *
     * @param Calculation $calculation
     * @return void
     */
    public function created(Calculation $calculation)
    {
        if ($calculation->calculationable_type == Appointment::class) {
            $appointment = $calculation->appointment;
            $appointment->markCalculated();
        }
    }

    /**
     * Handle the Calculation "updated" event.
     *
     * @param Calculation $calculation
     * @return void
     */
    public function updated(Calculation $calculation)
    {
        //
    }

    /**
     * Handle the Calculation "deleted" event.
     *
     * @param Calculation $calculation
     * @return void
     */
    public function deleted(Calculation $calculation)
    {
        //
    }

    /**
     * Handle the Calculation "restored" event.
     *
     * @param Calculation $calculation
     * @return void
     */
    public function restored(Calculation $calculation)
    {
        //
    }

    /**
     * Handle the Calculation "force deleted" event.
     *
     * @param Calculation $calculation
     * @return void
     */
    public function forceDeleted(Calculation $calculation)
    {
        //
    }
}
