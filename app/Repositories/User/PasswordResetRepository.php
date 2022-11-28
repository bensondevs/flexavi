<?php

namespace App\Repositories\User;

use App\Models\User\PasswordReset;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class PasswordResetRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new PasswordReset);
    }

    /**
     * Save password reset
     *
     * @param array $data
     * @return PasswordReset|null
     */
    public function save(array $data)
    {
        try {
            $model = $this->getModel();
            $model->fill($data);
            $model->save();
            $this->setModel($model);
            $this->setSuccess("Successfully store password reset.");
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError("Failed to store password reset.", $error);
        }

        return $this->getModel();
    }
}
