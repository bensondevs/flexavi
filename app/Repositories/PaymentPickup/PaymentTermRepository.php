<?php

namespace App\Repositories\PaymentPickup;

use App\Enums\PaymentTerm\PaymentTermStatus as Status;
use App\Models\PaymentPickup\PaymentTerm;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class PaymentTermRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        // $this->setInitModel(new PaymentTerm());
    }

    /**
     * Save payment term by supplied input array
     *
     * @param  array  $termData
     * @return PaymentTerm|null
     */
    public function save(array $termData)
    {
        try {
            $term = $this->getModel();
            $term->fill($termData);
            $term->save();
            $this->setModel($term);
            $this->setSuccess('Successfully save payment term.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save payment term.', $error);
        }

        return $this->getModel();
    }

    /**
     * Mark payment term as paid
     *
     * @return PaymentTerm|null
     */
    public function markAsPaid()
    {
        try {
            $term = $this->getModel();
            $term->status = Status::Paid;
            $term->save();
            $this->setModel($term);
            $this->setSuccess('Payment term has been settled.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to settle payment term.', $error);
        }

        return $this->getModel();
    }

    /**
     * Cancel paid status of payment term
     *
     * @return PaymentTerm|null
     */
    public function cancelPaidStatus()
    {
        try {
            $term = $this->getModel();
            $term->status = Status::Unpaid;
            $term->save();
            $this->setModel($term);
            $this->setSuccess('Successfully cancel paid status');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to cancel paid status', $error);
        }

        return $this->getModel();
    }

    /**
     * Forward payment term to debt collectior
     *
     * @return PaymentTerm|null
     */
    public function forwardToDebtCollector()
    {
        try {
            $term = $this->getModel();
            $term->status = Status::ForwardedToDebtCollector;
            $term->save();
            $this->setModel($term);
            $this->setSuccess(
                'Successfully forward payment term to debt collector.'
            );
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $message = 'Failed to forward payment term to debt collector.';
            $this->setError($message, $error);
        }

        return $this->getModel();
    }

    /**
     * Delete payment term
     *
     * @param  bool  $force
     * @return bool
     */
    public function delete(bool $force = false)
    {
        try {
            $term = $this->getModel();
            $force ? $term->forceDelete() : $term->delete();
            $this->destroyModel();
            $this->setSuccess('Successfully delete payment term.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to delete payment term.', $error);
        }

        return $this->returnResponse();
    }
}
