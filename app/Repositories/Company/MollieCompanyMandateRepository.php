<?php

namespace App\Repositories\Company;

use App\Models\Company\MollieCompanyMandate;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class MollieCompanyMandateRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new MollieCompanyMandate());
    }

    /**
     * Save mollie company mandate
     *
     * @return MollieCompanyMandate|null
     */
    public function save(array $input)
    {
        try {
            $mandate = $this->getModel();
            $mandate->fill([
                'user_id' => $input['user_id'],
                'company_id' => $input['company_id'],
                'mandate_id' => $input['mandate_id'],
            ]);
            $mandate->save();
            $this->setModel($mandate);
            $this->setSuccess('Successfully add card.');
        } catch (QueryException $th) {
            $this->setError('Failed to add card.');
        }

        return $this->getModel();
    }

    /**
     * Revoke mandate from user
     *
     * @return bool
     */
    public function revoke()
    {
        try {
            $mandate = $this->getModel();
            $mandate->delete();
            $this->setSuccess('Successfully revoke card.');
        } catch (QueryException $th) {
            $this->setError('Failed to revoke card.');
        }

        return $this->returnResponse();
    }
}
