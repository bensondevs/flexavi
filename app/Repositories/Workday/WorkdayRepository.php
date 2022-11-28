<?php

namespace App\Repositories\Workday;

use App\Models\{Company\Company, Workday\Workday};
use App\Repositories\Base\BaseRepository;
use DateTime;
use Illuminate\Database\QueryException;

class WorkdayRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new Workday());
    }

    /**
     * Generate workdays between range
     *
     * @param  DateTime|null  $start
     * @param  DateTime|null  $end
     * @return bool
     */
    public function generateWorkdays($start = null, $end = null)
    {
        try {
            $start = $start ?: now()->startOfMonth();
            $end =
                $end ?:
                now()
                    ->endOfMonth()
                    ->addDay();
            foreach (Company::all() as $company) {
                $rawWorkdays = [];
                for (
                    $date = $start->copy();
                    $date->lte($end);
                    $date->addDay()
                ) {
                    $rawWorkdays[] = [
                        'id' => generateUuid(),
                        'company_id' => $company->id,
                        'date' => $date->copy(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                Workday::insert($rawWorkdays);
            }
            $this->setSuccess('Successfully generate workdays');
        } catch (QueryException $qe) {
            $this->setError('Failed to generate workdays');
        }

        return $this->returnResponse();
    }

    /**
     * Get current workday
     *
     * @param  Company $company
     * @return Workday|null
     */
    public function current(Company $company)
    {
        return Workday::where('company_id', $company->id)
            ->where('date', now()->toDateString())
            ->first();
    }

    /**
     * Process workday and set it's status to processed
     *
     * @return Workday|null
     */
    public function process()
    {
        try {
            $workday = $this->getModel();
            $workday->process();
            $this->setModel($workday);
            $this->setSuccess('Successfully process workday.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to process workday.', $error);
        }

        return $this->getModel();
    }

    /**
     * Set status of workday to be calculated
     *
     * @return Workday|null
     */
    public function calculate()
    {
        try {
            $workday = $this->getModel();
            $workday->calculate();
            $this->setModel($workday);
            $this->setSuccess('Successfully calculate workday.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to calculate workday.', $error);
        }

        return $this->getModel();
    }
    /**
     * Delete workday
     *
     * @return bool
     */
    public function delete(bool $forceDelete = false)
    {
        try {
            $workday = $this->getModel();
            $forceDelete ? $workday->forceDelete() : $workday->delete();
            $this->destroyModel();
            $this->setSuccess('Successfully delete workday.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to delete workday.', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Restore workday.
     *
     * @return Workday|null
     */
    public function restore()
    {
        try {
            $workday = $this->getModel();
            $workday->restore();
            $this->setModel($workday);
            $this->setSuccess('Successfully restore workday.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to restore workday.', $error);
        }

        return $this->getModel();
    }
}
