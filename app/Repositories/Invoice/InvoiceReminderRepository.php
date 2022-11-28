<?php

namespace App\Repositories\Invoice;

use App\Models\Invoice\InvoiceReminder;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class InvoiceReminderRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new InvoiceReminder());
    }

    /**
     * Save invoice reminder
     *
     * @param array $data
     * @return InvoiceReminder|null
     */
    public function save(array $data = []): ?InvoiceReminder
    {
        try {
            $invoiceReminder = $this->getModel();
            $invoiceReminder->fill($data);
            $invoiceReminder->save();
            $this->setModel($invoiceReminder);
            $this->setSuccess("Successfully saved invoice reminder");
        } catch (QueryException $e) {
            $error = $e->getMessage();
            $this->setError("Failed to save invoice reminder.", $error);
        }
        return $this->getModel();
    }
}
