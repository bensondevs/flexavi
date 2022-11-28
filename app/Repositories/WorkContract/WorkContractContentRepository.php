<?php

namespace App\Repositories\WorkContract;

use App\Models\WorkContract\WorkContractContent;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class WorkContractContentRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new WorkContractContent());
    }

    /**
     * Save for create or update work contract content
     *
     * @param array $data
     * @return WorkContractContent|null
     */
    public function save(array $data): ?WorkContractContent
    {
        try {
            $model = $this->getModel();
            $model->fill($data);
            $model->save();

            $this->setModel($model->fresh());
            $this->setSuccess('Successfully save work contract content.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save work contract content.', $error);
        }

        return $this->getModel();
    }

}
