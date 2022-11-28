<?php

namespace App\Repositories\Work;

use App\Models\{Appointment\Appointment, Appointment\SubAppointment, Quotation\Quotation, Work\Work};
use App\Repositories\Base\BaseRepository;
use App\Repositories\ExecuteWork\ExecuteWorkRepository;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class WorkRepository extends BaseRepository
{
    /**
     * Create New Repository Instance
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new Work());
    }

    /**
     * Get all appointment works
     *
     * @param  Appointment  $appointment
     * @param  array  $options
     * @param  bool  $paginate
     * @return Collection
     */
    public function appointmentWorks(
        Appointment $appointment,
        array $options = [],
        bool $paginate = false
    ) {
        $works = $appointment->works;
        $this->setModel($works);

        return $this->all($options, $paginate, true);
    }

    /**
     * Get all sub-appointment works
     *
     * @param  SubAppointment  $subAppointment
     * @param  array  $options
     * @param  bool  $paginate
     * @return Collection
     */
    public function subAppointmentWorks(
        SubAppointment $subAppointment,
        array $options = [],
        bool $paginate = false
    ) {
        $works = $subAppointment->works;
        $this->setModel($works);

        return $this->all($options, $paginate, true);
    }

    /**
     * Get all quotation works
     *
     * @param  Quotation  $quotation
     * @param  array  $options
     * @param  bool  $paginate
     * @return Collection
     */
    public function quotationWorks(
        Quotation $quotation,
        array $options = [],
        bool $paginate = false
    ) {
        $works = $quotation->works;
        $this->setModel($works);

        return $this->all($options, $paginate, true);
    }

    /**
     * Save work model to database
     *
     * @param  array  $workData
     * @return Work|null
     */
    public function save(array $workData)
    {
        try {
            $work = $this->getModel();
            $work->fill($workData);
            $work->save();
            $this->setModel($work);
            $this->setSuccess('Successfully save work.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to create work.', $error);
        }

        return $this->getModel();
    }

    /**
     * Attach work model to any work attachable model
     *
     * @param  mixed $workable
     * @return Work|null
     */
    public function attachTo($workable)
    {
        try {
            $work = $this->getModel();
            $work
                ->{get_plural_lower_class($workable)}()
                ->attach($workable, ['id' => generateUuid()]);
            $this->setModel($work);
            $this->setSuccess('Successfully attach work.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to attach work.', $error);
        }

        return $this->getModel();
    }

    /**
     * Attach many works instance to any work attachable
     *
     * @param mixed $workable
     * @param array $works
     */
    public function attachToMany($workable, array $works)
    {
        try {
            foreach ($works as $work) {
                $workable->works()->attach($work, ['id' => generateUuid()]);
            }
            $this->setSuccess('Successfully attach many works.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to attach many works.', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Detach work instance from any work attachable
     *
     * @param  mixed $workable
     * @return Work|null
     */
    public function detachFrom($workable)
    {
        try {
            $work = $this->getModel();
            $workable->works()->detach($work);
            $this->setModelSuccess($work, 'Successfully detach work.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to detach work.', $error);
        }

        return $this->getModel();
    }

    /**
     * Detach many work instances from any work attachable
     *
     * @param mixed $workable
     * @param mixed $works
     */
    public function detachManyFrom($workable, $works)
    {
        try {
            $workable->works()->detach($works);
            $this->setSuccess('Successfully detach many works.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to detach many works.', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Truncate work instances attached to any work attachable
     *
     * @param mixed $workable
     */
    public function truncate($workable)
    {
        try {
            $workable->works()->detach();
            $this->setSuccess('Successfully truncate works.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to truncate works.', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Set work status to "InProcess"
     *
     * @param  Appointment $appointment
     * @return Work|null
     */
    public function execute(Appointment $appointment, array $executionData = [])
    {
        try {
            DB::beginTransaction();
            $work = $this->getModel();
            $work->execute($appointment);
            $executionData['company_id'] = $work->company_id;
            $executionData['work_id'] = $work->id;
            $executionData['appointment_id'] = $appointment->id;
            $executeWorkRepository = new ExecuteWorkRepository();
            $executeWorkRepository->execute($executionData);
            DB::commit();
        } catch (QueryException $qe) {
            DB::rollBack();
            $error = $qe->getMessage();
            $this->setError('Failed to execite work.', $error);
        }

        return $this->getModel();
    }

    /**
     * Set work status to "Unfinished"
     *
     * @param string $unfinishNote
     * @return Work|null
     */
    public function markUnfinish(string $unfinishNote = '')
    {
        try {
            $work = $this->getModel();
            $work->markUnfinished($unfinishNote);
            $this->setModel($work);
            $this->setSuccess('Successfully mark work as unfinished.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to mark work as unfinished.', $error);
        }

        return $this->getModel();
    }

    /**
     * Set work status to "Finished"
     *
     * @param  Appointment $appointment
     * @param  string $finishNote
     * @return Work|null
     */
    public function markFinish(
        Appointment $appointment,
        string $finishNote = ''
    ) {
        try {
            $work = $this->getModel();
            $work->markFinished($appointment, $finishNote);
            $this->setModel($work);
            $this->setSuccess('Successfully mark work as finished.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to mark work as finsihed.', $error);
        }

        return $this->getModel();
    }

    /**
     * Delete record of the model set in repository
     *
     * @param  bool  $force
     * @return bool
     */
    public function delete(bool $force = false)
    {
        try {
            $work = $this->getModel();
            $force ? $work->forceDelete() : $work->delete();
            $this->destroyModel();
            $this->setSuccess('Successfully delete work.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to delete work.', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Restore record of the model set in repository
     *
     * @return Work|null
     */
    public function restore()
    {
        try {
            $work = $this->getModel();
            $work->restore();
            $this->setModel($work);
            $this->setSuccess('Successfully restore work.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to restore work.', $error);
        }

        return $this->getModel();
    }
}
