<?php

namespace App\Jobs\Workday;

use App\Jobs\Test\SyncWorkdayAppointments;
use App\Models\{Appointment\Appointment, Company\Company, Workday\Workday};
use App\Services\Workday\GenerateWorkdayService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateWorkdayByAppointmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Company model container
     *
     * @var \App\Models\Company\Company
     */
    private $company;

    /**
     * Appointment model container
     *
     * @var \App\Models\Appointment\Appointment
     */
    private $appointment;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\Company\Company  $company
     * @param \App\Models\Appointment\Appointment $appointment
     * @return void
     */
    public function __construct(Company $company, Appointment $appointment)
    {
        $this->company = $company;
        $this->appointment = $appointment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $company = $this->company;
        $appointment = $this->appointment;

        $latestWorkday = Workday::where('company_id', $company->id)->latest('date')->first();
        $startWorkday = $this->getStartOfWorkday($latestWorkday, $appointment->start);


        $generateWorkdaysService = new GenerateWorkdayService;
        $generateWorkdaysService->handle(
            [
                $company->id
            ],
            $startWorkday,
            $appointment->end
        );

        $syncWorkdayAppointments = new SyncWorkdayAppointments();
        $syncWorkdayAppointments->delay(1);
        dispatch($syncWorkdayAppointments);
    }

    /**
     * Get start of workday
     *
     * @param \App\Models\Workday\Workday|null $latestWorkday
     * @param string $appointmentStart
     * @return string
     */
    private function getStartOfWorkday($latestWorkday, $appointmentStart)
    {
        if (is_null($latestWorkday)) return $appointmentStart;
        if ($latestWorkday->start > $appointmentStart) return $appointmentStart;
        return $latestWorkday->start;
    }
}
