<?php

namespace App\Repositories\Setting;

use App\Models\Setting\WorkContractSetting;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class WorkContractSettingRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new WorkContractSetting());
    }

    /**
     * Save work contract setting
     *
     * @param array $data
     * @return WorkContractSetting|null
     */
    public function save(array $data): ?WorkContractSetting
    {
        try {
            $model = $this->getModel();
            $model->fill($data);
            $model->save();
            if (isset($data['signature'])) {
                $model->clearMediaCollection('signature');
                $model->addMedia($data['signature'])
                ->usingName($data['signature_name'])
                ->toMediaCollection('signature');
            }
            $this->setModel($model);
            $this->setSuccess('Successfully save work contract setting.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save work contract setting.', $error);
        }
        return $this->getModel();
    }
}
