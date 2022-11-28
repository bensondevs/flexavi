<?php

namespace App\Repositories\Quotation;

use App\Enums\Quotation\QuotationStatus;
use App\Models\{Quotation\Quotation};
use App\Repositories\Base\BaseRepository;
use App\Services\Quotation\QuotationService;
use Illuminate\Database\QueryException;
use Illuminate\Http\UploadedFile;

class QuotationRepository extends BaseRepository
{
    /**
     * Repository class constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new Quotation());
    }

    /**
     * Delete or force delete quotation
     * To do force delete, set the parameter to TRUE
     *
     * @param bool $force
     * @return bool
     */
    public function delete(bool $force = false): bool
    {
        try {
            $quotation = $this->getModel();
            $force === true ? $quotation->forceDelete() : $quotation->delete();
            $this->destroyModel();
            $this->setSuccess('Successfully delete quotation.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to delete quotation.', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Save Quotation as a draft
     *
     * @param array $quotationData
     * @return Quotation|null
     */
    public function draft(array $quotationData): ?Quotation
    {
        try {
            $quotation = $this->getModel();
            $quotation->fill($quotationData);
            $quotation->status = QuotationStatus::Drafted;
            $quotation->save();
            $this->setModel($quotation);
            $this->setSuccess('Successfully save quotation data.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save quotation data.', $error);
        }

        return $this->getModel();
    }

    /**
     * Save quotation as Created (not as draft)
     *
     * @param array $quotationData
     * @return Quotation|null
     */
    public function save(array $quotationData): ?Quotation
    {
        try {
            $quotation = $this->getModel();
            $quotation->fill($quotationData);
            $quotation->save();
            $this->setModel($quotation->fresh());
            $this->setSuccess('Successfully save quotation data.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save quotation data.', $error);
        }

        return $this->getModel();
    }

    /**
     * Send quotation through email and
     * set the status of quotation \App\Enums\Quotation\QuotationStatus::Sent
     *
     * @return Quotation|null
     */
    public function send(): ?Quotation
    {
        try {
            $quotation = $this->getModel();
            if (!in_array($quotation->status, [QuotationStatus::Signed, QuotationStatus::Nullified])) {
                $quotation->fill([
                    'status' => QuotationStatus::Sent,
                    'sent_at' => now(),
                ]);
            }

            $quotation->save();
            $quotation->refresh();

            $this->setModel($quotation);

            if (!$quotation->customer?->email) {
                $this->setSuccess('Successfully set status to sent. Warning: THE CUSTOMER DOES NOT HAVE EMAIL.');
                return $this->getModel();
            }

            app(QuotationService::class)
                ->sendQuotationMail($quotation);
            $this->setSuccess('Successfully send quotation to customer.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed send quotation to customer.', $error);
        }

        return $this->getModel();
    }

    /**
     * nullified a Quotation status
     *
     * @return ?Quotation
     */
    public function nullify(): ?Quotation
    {
        try {
            $quotation = $this->getModel();
            $quotation->setNullified();
            $this->setModel($quotation->fresh());
            $this->setSuccess('Successfully set Quotation status as "Nullified".');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to set Quotation status as "Nullified".', $error);
        }

        return $this->getModel();
    }

    /**
     * Restore soft-deleted quotation
     *
     * @return Quotation|null
     */
    public function restore(): ?Quotation
    {
        try {
            $quotation = $this->getModel();
            $quotation->restore();
            $this->setModel($quotation);
            $this->setSuccess('Successfully restore quotation');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to restore quotation', $error);
        }

        return $this->getModel();
    }

    /**
     * Save signed doc
     *
     * @param UploadedFile $file
     * @return ?Quotation
     */
    public function saveSignedDocument(UploadedFile $file): ?Quotation
    {
        try {
            $quotation = $this->getModel();
            $quotation->setSigned();
            $quotation->clearMediaCollection('signed_document');
            $quotation->addMedia($file)->toMediaCollection('signed_document');
            $this->setModel($quotation);
            $this->setSuccess("Successfully save signed doc.");
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError("Failed to save signed doc.", $error);
        }
        return $this->getModel();
    }

    /**
     * Remove signed doc
     *
     * @return ?Quotation
     */
    public function removeSignedDocument(): ?Quotation
    {
        try {
            $quotation = $this->getModel();

            // Remove signed document
            $quotation->clearMediaCollection('signed_document');

            // Set status to sent if status is signed
            if ($quotation->isSigned()) {
                $quotation->status = QuotationStatus::Sent;
                $quotation->save();
            }

            $this->setModel($quotation);
            $this->setSuccess("Successfully remove signed doc.");
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError("Failed to remove signed doc.", $error);
        }
        return $this->getModel();
    }
}
