<?php

namespace App\Repositories\Quotation;

use App\Models\Quotation\QuotationItem;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class QuotationItemRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new QuotationItem());
    }

    /**
     * Save for create or update quotation item
     *
     * @param array $data
     * @return QuotationItem|null
     */
    public function save(array $data): ?QuotationItem
    {
        try {
            $model = $this->getModel();
            $model->fill($data);
            $model->save();
            $this->setModel($model->fresh());
            $this->setSuccess('Successfully save work quotation item.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save work quotation item.', $error);
        }
        return $this->getModel();
    }

}
