<?php

namespace App\Repositories\Pricing;

use App\Models\Pricing\Pricing;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class PricingRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new Pricing());
    }

    /**
     * Save pricing
     *
     * @param  array  $pricingData
     * @return Pricing|null
     */
    public function save(array $pricingData)
    {
        try {
            $pricing = $this->getModel();
            $pricing->fill($pricingData);
            $pricing->save();
            $this->setModel($pricing);
            $this->setSuccess('Successfully save pricing.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save pricing.', $error);
        }

        return $this->getModel();
    }

    /**
     * Delete pricing
     *
     * @return bool
     */
    public function delete(bool $force = false)
    {
        try {
            $pricing = $this->getModel();
            $force ? $pricing->forceDelete() : $pricing->delete();
            $this->destroyModel();
            $this->setSuccess('Successfully delete pricing');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to delete pricing.', $error);
        }

        return $this->returnResponse();
    }
}
