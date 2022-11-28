<?php

namespace App\Repositories\Setting;

use App\Models\Setting\WorkContractContentSetting;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class WorkContractContentSettingRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new WorkContractContentSetting());
    }

    /**
     * Save work contract content setting
     *
     * @param array $data
     * @return WorkContractContentSetting|null
     */
    public function save(array $data): ?WorkContractContentSetting
    {
        try {
            $model = $this->getModel();
            $model->fill($data);
            $model->save();
            $this->setModel($model);
            $this->setSuccess('Successfully save work contract content setting.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save work contract content setting.', $error);
        }
        return $this->getModel();
    }

}
