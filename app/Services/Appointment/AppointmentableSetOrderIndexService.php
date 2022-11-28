<?php

namespace App\Services\Appointment;

use App\Models\Appointment\Appointmentable;
use App\Repositories\Appointment\AppointmentableRepository;

class AppointmentableSetOrderIndexService
{
    /**
     * Appointmentable Repository Container
     *
     * @var AppointmentableRepository
     */
    private AppointmentableRepository $appointmentable;

    /**
     * Service constructor method
     *
     * @param AppointmentableRepository $appointmentableRepository
     * @return void
     */
    public function __construct(AppointmentableRepository $appointmentableRepository)
    {
        $this->appointmentable = $appointmentableRepository;
    }

    /**
     * Set order index for worklist appointments
     *
     * @param mixed $data
     */
    public function handle($data): void
    {
        foreach ($data->route as $index => $route) {
            if ($index > 0) {
                $appointmentableId = explode('###', $route->name)[1];
                $appointmentable = Appointmentable::find($appointmentableId);
                $this->appointmentable->setModel($appointmentable);
                $this->appointmentable->setOrderIndex($index);
            }
        }
    }
}
