<?php

namespace App\Repositories\Revenue;

use App\Models\{Revenue\Revenue, Work\Work};
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class RevenueRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new Revenue());
    }

    /**
     * Save revenue using supplied data in parameter
     *
     * @return Revenue|null
     */
    public function save(array $revenueData = [])
    {
        try {
            $revenue = $this->getModel();
            $revenue->fill($revenueData);
            $revenue->save();
            $this->setModel($revenue);
            $this->setSuccess('Successfully save revenue.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save revenue.', $error);
        }

        return $this->getModel();
    }

    /**
     * Record revenue from work
     *
     * @param  Work  $work
     * @param  array  $revenueData
     * @return Revenue|null
     */
    public function recordWork(Work $work, array $revenueData = [])
    {
        try {
            $revenueData['company_id'] = $work->company_id;
            $revenueData['revenue_name'] = isset($revenueData['revenue_name'])
                ? $revenueData['revenue_name']
                : $work->description;
            $revenueData['amount'] = $work->total_price;
            $revenueData['paid_amount'] = isset($revenueData['paid_amount'])
                ? $revenueData['paid_amount']
                : 0;
            $revenue = $this->save($revenueData);
            $work->attachRevenue($revenue);
            $this->setSuccess('Successfully record revenue.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to record revenue.', $error);
        }

        return $this->getModel();
    }

    /**
     * Delete revenue
     *
     * @param  bool  $force
     * @return bool
     */
    public function delete(bool $force = false)
    {
        try {
            $revenue = $this->getModel();
            $force ? $revenue->forceDelete() : $revenue->delete();
            $this->destroyModel();
            $this->setSuccess('Successfully delete revenue.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to delete revenue.', $error);
        }

        return $this->returnResponse();
    }
}
