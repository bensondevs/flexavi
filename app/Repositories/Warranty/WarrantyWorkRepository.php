<?php

namespace App\Repositories\Warranty;

use App\Models\Warranty\Warranty;
use App\Models\Warranty\WarrantyWork;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class WarrantyWorkRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new WarrantyWork());
    }

    /**
     * Attach new work to warranty
     *
     * @param  Warranty  $warranty
     * @param  array  $warrantyWorkData
     * @return WarrantyWork|null
     */
    public function attach(Warranty $warranty, array $warrantyWorkData)
    {
        try {
            $warrantyWork = new WarrantyWork($warrantyWorkData);
            $warrantyWork->warranty_id = $warranty->id;
            $warrantyWork->save();
            $this->setModel($warrantyWork);
            $this->setSuccess('Successfully attach warranty work to warranty');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to attach warranty work.', $error);
        }

        return $this->getModel();
    }

    /**
     * Multiple attach work to warranty
     *
     * @param  Warranty  $warranty
     * @param  array  $warrantyWorksData
     * @return Collection
     */
    public function multipleAttach(
        Warranty $warranty,
        array $warrantyWorksData = []
    ) {
        $rawWarrantyWorks = new Collection();
        try {
            foreach ($warrantyWorksData as $warrantyWorkData) {
                $rawWarrantyWorks->push($warrantyWorkData);
            }
            $warranty->warrantyWorks()->saveMany($rawWarrantyWorks);
            $this->setSuccess('Successfully attach many warranty works data.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError(
                'Failed to attach many warranty works data.',
                $error
            );
        }

        return $rawWarrantyWorks;
    }

    /**
     * Update work warranty
     *
     * @param  array  $warrantyWorkData
     * @return WarrantyWork|null
     */
    public function update(array $warrantyWorkData)
    {
        try {
            $warrantyWork = $this->getModel();
            $warrantyWork->fill($warrantyWorkData);
            $warrantyWork->save();
            $this->setModel($warrantyWork);
            $this->setSuccess('Successfully update warranty work.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to update warranty work.', $error);
        }

        return $this->getModel();
    }

    /**
     * Detach warranty work from the warranty
     *
     * @param  bool $force
     * @return bool
     */
    public function detach(bool $force = false)
    {
        try {
            $warrantyWork = $this->getModel();
            $force ? $warrantyWork->forceDelete() : $warrantyWork->delete();
            $this->destroyModel();
            $this->setSuccess('Successfully detach warranty work.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to detach warranty work.', $error);
        }

        return $this->returnResponse();
    }
}
