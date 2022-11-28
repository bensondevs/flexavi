<?php

namespace App\Services\Quotation;

use App\Enums\Invoice\InvoiceStatus;
use App\Jobs\SendMail;
use App\Mail\Quotation\QuotationMail;
use App\Models\Customer\Customer;
use App\Models\Quotation\Quotation;
use App\Models\WorkService\WorkService;
use App\Repositories\Invoice\InvoiceRepository;
use App\Repositories\Quotation\QuotationItemRepository;
use App\Repositories\Quotation\QuotationRepository;
use App\Services\Invoice\InvoiceService;
use Exception;

class QuotationService
{
    /**
     * Quotation Repository Class Container
     *
     * @var QuotationRepository
     */
    private QuotationRepository $quotationRepository;

    /**
     * Quotation Item Repository Class Container
     *
     * @var QuotationItemRepository
     */
    private QuotationItemRepository $quotationItemRepository;

    /**
     * Create New Service Instance
     *
     * @param QuotationRepository $quotationRepository
     * @param QuotationItemRepository $quotationItemRepository
     */
    public function __construct(
        QuotationRepository     $quotationRepository,
        QuotationItemRepository $quotationItemRepository
    )
    {
        $this->quotationRepository = $quotationRepository;
        $this->quotationItemRepository = $quotationItemRepository;
    }

    /**
     * Send quotation to customer mail
     *
     * @param Quotation $quotation
     * @return void
     */
    public function sendQuotationMail(Quotation $quotation): void
    {
        $quotation->refresh();

        $customer = (!$quotation->customer instanceof Customer) ?
            Customer::find($quotation->customer_id) :
            $quotation->customer;
        if (!$customer) {
            abort(
                500,
                'The quotation with ID: ' . $quotation->id . ' does not have customer attached to it.'
            );
        }

        $mailable = new QuotationMail($quotation);
        dispatch(new SendMail($mailable, $customer->email));
    }

    /**
     * Send Quotation
     *
     * @param Quotation $quotation
     * @return QuotationRepository
     */
    public function send(Quotation $quotation): QuotationRepository
    {
        \DB::beginTransaction();
        try {
            $this->quotationRepository->setModel($quotation);
            $this->quotationRepository->send();
            \DB::commit();
        } catch (Exception $exception) {
            \DB::rollBack();
            $this->quotationRepository->setError($exception->getMessage());
        }
        return $this->quotationRepository;
    }

    /**
     * Generate invoice from quotation
     *
     * @param Quotation $quotation
     * @return InvoiceRepository
     * @throws Exception
     */
    public function generateInvoice(Quotation $quotation): InvoiceRepository
    {
        $data = [
            'invoice_data' => [
                'customer_id' => $quotation->customer_id,
                'company_id' => $quotation->company_id,
                'customer_address_id' => $quotation->customer_address,
                'date' => now(),
                'discount_amount' => $quotation->discount_amount,
                'potential_amount' => $quotation->potential_amount,
                'status' => InvoiceStatus::Drafted,

            ],
            'invoice_items' => $quotation->items->map(function ($item) {
                return [
                    'work_service_id' => $item->work_service_id,
                    'amount' => $item->amount,
                ];
            })->toArray(),
        ];
        return app(InvoiceService::class)->save(invoiceData: $data);
    }

    /**
     * Save or Update Quotation
     *
     * @param Quotation|null $quotation
     * @param array $quotationData
     * @return QuotationRepository
     */
    public function save(
        ?Quotation $quotation = null,
        array      $quotationData = [],
    ): QuotationRepository
    {
        \DB::beginTransaction();
        try {
            if ($quotation) {
                $this->quotationRepository->setModel($quotation);
            }

            $quotation = $this->quotationRepository->save($quotationData['quotation_data']);

            foreach ($quotationData['quotation_items'] as $quotationItem) {
                $this->quotationItemRepository->destroyModel();
                $workService = WorkService::findOrFail($quotationItem['work_service_id']);
                $quotationItem['unit_price'] = $workService->price;
                $quotationItem['quotation_id'] = $quotation->id;
                $quotationItem['tax_percentage'] = $workService->tax_percentage;
                $quotationItem['total'] = $quotationItem['unit_price'] * $quotationItem['amount'];
                $this->quotationItemRepository->save($quotationItem);
            }

            $quotation->countWorksAmount();
            \DB::commit();
        } catch (Exception $exception) {
            $this->quotationRepository->setError($exception->getMessage());
            \DB::rollBack();
        }
        return $this->quotationRepository;
    }
}
