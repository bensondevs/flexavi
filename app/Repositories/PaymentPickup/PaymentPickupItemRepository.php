<?php

namespace App\Repositories\PaymentPickup;

use App\Models\PaymentPickup\PaymentPickupItem;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class PaymentPickupItemRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new PaymentPickupItem);
    }

    /**
     * Save or update payment pickup item
     *
     * @param array  $data
     * @return PaymentPickupItem|null
     */
    public function save(array $data = [])
    {
        try {
            $item = $this->getModel();
            $item->fill($data);
            $item->save();
            $this->setModel($item);
            $this->setSuccess('Successfully save payment item data.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save payment item data.', $error);
        }

        return $this->getModel();
    }
}
