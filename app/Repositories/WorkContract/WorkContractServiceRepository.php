<?php

namespace App\Repositories\WorkContract;

use App\Models\WorkContract\WorkContractService;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class WorkContractServiceRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new WorkContractService());
    }

    /**
     * Save for create or update work contract content
     *
     * @param array $data
     * @return WorkContractService|null
     */
    public function save(array $data): ?WorkContractService
    {
        try {
            $model = $this->getModel();
            $model->fill($data);
            $model->save();

            $this->setModel($model->fresh());
            $this->setSuccess('Successfully save work contract service.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save work contract service.', $error);
        }

        return $this->getModel();
    }

}
