<?php

namespace App\Repositories\PaymentPickup;

use App\Models\{PaymentPickup\PaymentReminder, PaymentPickup\PaymentReminderable};
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class PaymentReminderRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new PaymentReminder());
    }

    /**
     * Save payment reminder
     *
     * @param  array  $reminderData
     * @return PaymentReminder|null
     */
    public function save(array $reminderData)
    {
        try {
            $reminder = $this->getModel();
            $reminder->fill($reminderData);
            $reminder->save();
            $this->setModel($reminder);
            $this->setSuccess('Successfully save payment reminder.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save payment reminder', $error);
        }

        return $this->getModel();
    }

    /**
     * Add reminderable to be reminded
     *
     * @param  mixed  $reminderable
     * @return PaymentReminder|null
     */
    public function addReminderable($reminderable)
    {
        try {
            $reminder = $this->getModel();
            $reminder->reminderables()->attach($reminderable);
            $this->setModel($reminder);
            $this->setSuccess(
                'Successfully add reminderable to payment reminder.'
            );
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError(
                'Failed to add reminderable to payment reminder',
                $error
            );
        }

        return $this->getModel();
    }

    /**
     * Add multiple reminderable to be reminded
     *
     * @param  array  $reminderables
     * @return PaymentReminder|null
     */
    public function addMultipleReminderable(array $reminderables)
    {
        try {
            $reminder = $this->getModel();
            $rawMorphPivots = [];
            foreach ($reminderables as $reminderable) {
                array_push($rawMorphPivots, [
                    'id' => generateUuid(),
                    'payment_reminder_id' => $reminder->id,
                    'payment_reminderable_type' => get_class($reminderable),
                    'payment_reminderable_id' => $reminderable->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            PaymentReminderable::insert($rawMorphPivots);
            $reminder->load(['reminderables']);
            $this->setModel($reminder);
            $this->setSuccess('Successfully add multiple reminderable.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to add multiple reminderable.', $error);
        }

        return $this->getModel();
    }

    /**
     * Remove reminderable from the payment reminder
     *
     * @param  mixed  $reminderable
     * @return PaymentReminder|null
     */
    public function removeReminderable($reminderable)
    {
        try {
            $reminder = $this->getModel();
            $type = get_class($reminderable);
            if (!$type != PaymentReminderable::class) {
                $paymentReminderable = PaymentReminderable::wherePaymentReminder(
                    $reminder
                )
                    ->whereReminderable($reminderable)
                    ->firstOrFail();
            } else {
                $paymentReminderable = $reminderable;
            }
            $paymentReminderable->delete();
            $this->setModel($reminder);
            $this->setSuccess('Successfully remove reminderable.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to remove reminderable', $error);
        }

        return $this->getModel();
    }

    /**
     * Remove multiple reminderable from the payment reminder
     *
     * @param  array  $reminderables
     * @return PaymentReminder|null
     */
    public function removeMultipleReminderable(array $reminderables)
    {
        try {
            $reminder = $this->getModel();
            $ids = array_map(function ($reminderable) {
                if (isset($reminderable['id'])) {
                    return $reminderable['id'];
                }
            }, $reminderables);
            PaymentReminderable::whereIn(
                'payment_reminderable_id',
                $ids
            )->destroy();
            $this->setModel($reminder);
            $this->setSuccess('Successfully remove multple reminderables.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to remove multiple reminderables.', $error);
        }

        return $this->getModel();
    }

    /**
     * Truncate reminderable from the payment reminder
     *
     * @return PaymentReminder|null
     */
    public function truncate()
    {
        try {
            $reminder = $this->getModel();
            $reminder->reminderables()->delete();
            $this->setModel($reminder);
            $this->setSuccess('Successfully remove all reminderables.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to remove all reminderables.', $error);
        }

        return $this->getModel();
    }

    /**
     * Delete payment reminder
     *
     * @param  bool  $force
     * @return bool
     */
    public function delete(bool $force = false)
    {
        try {
            $reminder = $this->getModel();
            $force ? $reminder->forceDelete() : $reminder->delete();
            $this->destroyModel();
            $this->setSuccess('Successfully delete payment reminder.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to delete payment reminder.', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Restore payment reminder
     *
     * @return PaymentReminder|null
     */
    public function restore()
    {
        try {
            $reminder = $this->getModel();
            $reminder->restore();
            $this->setModel($reminder);
            $this->setSuccess('Successfully restore payment reminder.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to restore payment reminder.', $error);
        }

        return $this->getModel();
    }
}
