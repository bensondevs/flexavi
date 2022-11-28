<?php

namespace App\Repositories\Invoice;

use App\Models\Invoice\InvoiceItem;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class InvoiceItemRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new InvoiceItem());
    }

    /**
     * Create new invoice item
     *
     * @param array $data
     * @return InvoiceItem
     */

    /**
     * Save or update invoice item
     *
     * @param array $data
     * @return InvoiceItem|null
     */
    public function save(array $data): ?InvoiceItem
    {
        try {
            $item = $this->getModel();
            $item->fill($data);
            $item->save();
            $this->setModel($item->fresh());
            $this->setSuccess('Successfully save invoice item.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save invoice item.', $error);
        }

        return $this->getModel();
    }
}
