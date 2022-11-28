<?php

namespace App\Repositories\Setting;

use App\Repositories\Base\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class GeneralSettingRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @param string $modelClass
     * @return void
     */
    public function __construct(string $modelClass)
    {
        $this->setInitModel(new $modelClass());
    }

    /**
     * Save work contract content setting
     *
     * @param array $data
     * @return ?Model
     */
    public function save(array $data): ?Model
    {
        try {
            $model = $this->getModel();
            $model->fill($data);
            $model->save();
            $this->setModel($model);
            $this->setSuccess('Successfully save setting.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save setting.', $error);
        }
        return $this->getModel();
    }
}
