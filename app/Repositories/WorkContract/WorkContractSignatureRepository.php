<?php

namespace App\Repositories\WorkContract;

use App\Models\WorkContract\WorkContractSignature;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\UploadedFile;

class WorkContractSignatureRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new WorkContractSignature());
    }

    /**
     * Save for create or update work contract content
     *
     * @param array $data
     * @return WorkContractSignature|null
     */
    public function save(array $data): ?WorkContractSignature
    {
        try {
            $model = $this->getModel();
            $model->fill($data);
            $model->save();
            if (isset($data['file']) && $data['file'] instanceof UploadedFile) {
                $model->clearMediaCollection('signature');
                $model->addMedia($data['file'])
                    ->toMediaCollection('signature');
            }

            $this->setModel($model->fresh());
            $this->setSuccess('Successfully save work contract service.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save work contract service.', $error);
        }

        return $this->getModel();
    }

}
