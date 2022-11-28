<?php

namespace App\Repositories\Inspection;

use App\Models\Inspection\Inspection;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class InspectionRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new Inspection());
    }

    /**
     * Start inspection
     *
     * @return array
     */
    public function startInspection()
    {
        $question = Inspection::QUESTIONS[0][0];
        return [
            'number' => 1,
            'phase' => 1,
            'question' => $question['question'],
            'choices' => $question['choices'],
            'options' => $question['options'],
        ];
    }

    /**
     * Handle inspection on answer next
     *
     * @return void
     */
    public function handleNext(array $answer)
    {
        // TODO: complete handleNext logic
        $phase = $answer['phase'];
        $number = $answer['number'];
        $choice = isset($answer['choice']) ? $answer['choice'] : null;
        if ($phase == 1) {
            if ($number == 1) {
                $answer = $answer['answer'];
                if ($choice == 'yes') {
                    // Upload the image
                } elseif ($choice == 'no') {
                    //
                }
            }
        }
        if ($phase == 2) {
            //
        }
        if ($phase == 3) {
            //
        }
        if ($phase == 4) {
            //
        }
    }

    /**
     * Save inspection data
     *
     * @param  array  $inspectionData
     * @return Inspection|null
     */
    public function save(array $inspectionData)
    {
        try {
            $inspection = $this->getModel();
            $inspection->fill($inspectionData);
            $inspection->save();
            $this->setModel($inspection);
            $this->setSuccess('Successfully create inspection.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to create inspection.', $error);
        }

        return $this->getModel();
    }

    /**
     * Delete inspection
     *
     * @param  bool  $force
     * @return bool
     */
    public function delete(bool $force = false)
    {
        try {
            $inspection = $this->getModel();
            $force ? $inspection->forceDelete() : $inspection->delete();
            $this->destroyModel();
            $this->setSuccess('Successfully delete inspection.');
        } catch (QueryException $qe) {
            $this->setError('Failed to delete inspection.');
        }

        return $this->returnResponse();
    }

    /**
     * Restore soft-deleted inspection
     *
     * @return Inspection|null
     */
    public function restore()
    {
        try {
            $inspection = $this->getModel();
            $inspection->restore();
            $this->setModel($inspection);
            $this->setSuccess('Successfully restore inspection');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to restore inspection', $error);
        }

        return $this->getModel();
    }
}
