<?php

namespace App\Repositories\Invoice;

use App\Models\Setting\InvoiceSetting;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class InvoiceSettingRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new InvoiceSetting());
    }

    /**
     * Save invoice setting
     *
     * @param array $data
     * @return InvoiceSetting|null
     */
    public function save(array $data): ?InvoiceSetting
    {
        try {
            $model = $this->getModel();
            $model->fill($data);
            $model->save();
            $this->setModel($model->fresh());
            $this->setSuccess("Successfully saved invoice setting");
        } catch (QueryException $e) {
            $error = $e->getMessage();
            $this->setError("Failed to save invoice setting.", $error);
        }
        return $this->getModel();
    }
}
