<?php

namespace App\Repositories\User;

use App\Models\User\UserSocialiteAccount;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class UserSocialiteAccountRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new UserSocialiteAccount());
    }

    /**
     * Save socialite by supplied array input
     *
     * @param array $data
     * @return UserSocialiteAccount|null
     */
    public function save(array $data): ?UserSocialiteAccount
    {
        try {
            $socialite = $this->getModel();
            $socialite->fill($data);
            $socialite->save();
            $this->setModel($socialite);
            $this->setSuccess('Successfully save socialite data.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save socialite data', $error);
        }

        return $this->getModel();
    }
}
