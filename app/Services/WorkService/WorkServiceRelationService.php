<?php

namespace App\Services\WorkService;

use App\Enums\Invoice\InvoiceStatus;
use App\Enums\Quotation\QuotationStatus;
use App\Enums\WorkContract\WorkContractStatus;
use App\Models\Invoice\Invoice;
use App\Models\Quotation\Quotation;
use App\Models\WorkContract\WorkContract;
use App\Models\WorkService\WorkService;
use DB;

class WorkServiceRelationService
{
    /**
     * Handle work service changed price or tax percentages.
     * This will update all related invoices, quotations and work contracts.
     *
     * @param WorkService $workService
     * @return void
     */
    public function dataChanged(WorkService $workService): void
    {
        if (!$workService->wasChanged()) {
            return;
        }

        if ($workService->wasChanged('tax_percentage') or $workService->wasChanged('price')) {
            DB::transaction(function () use ($workService) {
                $this->syncQuotationsAmount($workService);
                $this->syncInvoicesAmount($workService);
                $this->syncWorkContractsAmount($workService);
            });
        }
    }

    /**
     * Sync quotations amount
     *
     * @param WorkService $workService
     * @return void
     */
    private function syncQuotationsAmount(WorkService $workService): void
    {
        $quotations = $workService->quotations()->where('status', QuotationStatus::Drafted)->get();
        $quotations->each(function (Quotation $quotation) use ($workService) {
            foreach ($quotation->items as $item) {
                if ($item->work_service_id === $workService->id) {
                    $item->unit_price = $workService->price;
                    $item->tax_percentage = $workService->tax_percentage;
                    $item->total = $item->amount * $workService->price;
                    $item->save();
                }
            }
            $quotation->fresh()->countWorksAmount();
        });
    }

    /**
     * Sync invoices amount
     *
     * @param WorkService $workService
     * @return void
     */
    private function syncInvoicesAmount(WorkService $workService): void
    {
        $invoices = $workService->invoices()->where('status', InvoiceStatus::Drafted)->get();
        $invoices->each(function (Invoice $invoice) use ($workService) {
            foreach ($invoice->items as $item) {
                if ($item->work_service_id === $workService->id) {
                    $item->unit_price = $workService->price;
                    $item->tax_percentage = $workService->tax_percentage;
                    $item->total = $item->amount * $workService->price;
                    $item->save();
                }
            }
            $invoice->fresh()->countWorksAmount();
        });
    }

    /**
     * Sync work contracts amount
     *
     * @param WorkService $workService
     * @return void
     */
    private function syncWorkContractsAmount(WorkService $workService): void
    {
        $workContracts = $workService->workContracts()->where('status', WorkContractStatus::Drafted)->get();
        $workContracts->each(function (WorkContract $workContract) use ($workService) {
            foreach ($workContract->services as $item) {
                if ($item->work_service_id === $workService->id) {
                    $item->unit_price = $workService->price;
                    $item->tax_percentage = $workService->tax_percentage;
                    $item->total = $item->amount * $workService->price;
                    $item->save();
                }
            }
            $workContract->fresh()->countWorksAmount();
        });
    }
}
