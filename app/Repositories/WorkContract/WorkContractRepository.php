<?php

namespace App\Repositories\WorkContract;

use App\Enums\WorkContract\WorkContractStatus;
use App\Jobs\SendMail;
use App\Mail\WorkContract\WorkContractMail;
use App\Models\Setting\WorkContractSetting;
use App\Models\WorkContract\WorkContract;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\UploadedFile;

class WorkContractRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new WorkContract());
    }

    /**
     * Set work contract status
     *
     * @return WorkContract|null
     */
    public function setAsDraft(): ?WorkContract
    {
        try {
            $workContract = $this->getModel();
            $workContract->status = WorkContractStatus::Drafted;
            $workContract->save();

            $this->setModel($workContract->fresh());
            $this->setSuccess('Successfully save work contract.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save work contract.', $error);
        }
        return $this->getModel();
    }

    /**
     * Save for create or update work contract
     *
     * @param array $data
     * @return WorkContract|null
     */
    public function save(array $data): ?WorkContract
    {
        try {
            $setting = WorkContractSetting::where('company_id', $data['company_id'])->first();
            $potentialAmount = $data['potential_amount'] ?? 0;
            $data['potential_amount'] = $potentialAmount;

            $signatureUseType = $data['signature_use_type'] ?? 'old';
            $signature = $data['signature'] ?? null;

            unset($data['signature_use_type']);
            unset($data['signature']);

            $workContract = $this->getModel();
            $workContract->fill($data);

            $workContract->save();


            if ($signatureUseType === 'upload' && ($signature instanceof UploadedFile)) {
                $workContract->clearMediaCollection('signature');
                $workContract->addMedia($signature)
                    ->usingName($data['signature_name'])
                    ->toMediaCollection('signature');
            }
            if ($signatureUseType === 'default' or $signatureUseType === 'old') {
                if (!$workContract->getFirstMedia('signature')) {
                    $workContract->clearMediaCollection('signature');
                    $signature = $setting->getFirstMedia('signature');
                    $signature->copy($workContract, 'signature');
                }
            }

            $this->setModel($workContract->fresh());
            $this->setSuccess('Successfully save work contract.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save work contract.', $error);
        }

        return $this->getModel();
    }

    /**
     * Set work contract status
     *
     * @return WorkContract|null
     */
    public function setAsCreated(): ?WorkContract
    {
        try {
            $workContract = $this->getModel();
            $workContract->status = WorkContractStatus::Drafted;
            $workContract->save();

            $this->setModel($workContract->fresh());
            $this->setSuccess('Successfully save work contract.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save work contract.', $error);
        }
        return $this->getModel();
    }

    /**
     * Set work contract status to send
     *
     * @return WorkContract|null
     */
    public function send(): ?WorkContract
    {
        try {
            $workContract = $this->getModel();
            if (!in_array($workContract->status, [WorkContractStatus::Signed, WorkContractStatus::Nullified])) {
                $workContract->status = WorkContractStatus::Sent;
                $workContract->sent_at = now();
            }
            $workContract->save();
            $mailable = new WorkContractMail($workContract);
            $job = new SendMail($mailable, $workContract->customer->email);
            dispatch($job);
            $this->setModel($workContract->fresh());
            $this->setSuccess('Successfully save work contract.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save work contract.', $error);
        }
        return $this->getModel();
    }

    /**
     * Set work contract as nullify
     *
     * @return WorkContract|null
     */
    public function nullify(): ?WorkContract
    {
        try {
            $workContract = $this->getModel();
            $workContract->status = WorkContractStatus::Nullified;
            $workContract->nullified_at = now();
            $workContract->save();

            $this->setModel($workContract->fresh());
            $this->setSuccess('Successfully save work contract.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save work contract.', $error);
        }
        return $this->getModel();
    }

    /**
     * Set work contract as print
     *
     * @return WorkContract|null
     */
    public function print(): ?WorkContract
    {
        try {
            $workContract = $this->getModel();
            if ($workContract->isDrafted()) {
                $workContract->status = WorkContractStatus::Sent;
                $workContract->sent_at = now();
                $workContract->save();
                $this->setModel($workContract->fresh());
            }

            $this->setSuccess('Successfully print work contract.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to print work contract.', $error);
        }
        return $this->getModel();
    }

    /**
     * Delete work contract
     *
     * @param bool $force
     * @return bool
     */
    public function delete(bool $force = false): bool
    {
        try {
            $contract = $this->getModel();
            $force ? $contract->forceDelete() : $contract->delete();
            $this->destroyModel();
            $this->setSuccess('Successfully delete work contract.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to delete work contract.', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Delete work contract
     *
     * @return bool
     */
    public function restore(): bool
    {
        try {
            $contract = $this->getModel();
            $contract->restore();
            $this->setSuccess('Successfully restore work contract.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to restore work contract.', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Upload signed document
     *
     * @param UploadedFile $signedDocument
     * @return WorkContract|null
     */
    public function uploadSignedDocument(UploadedFile $signedDocument): ?WorkContract
    {
        try {
            $workContract = $this->getModel();
            $workContract->signed_at = now();
            $workContract->status = WorkContractStatus::Signed;
            $workContract->clearMediaCollection('signed_document');
            $workContract->addMedia($signedDocument)
                ->toMediaCollection('signed_document');
            $workContract->save();
            $this->setModel($workContract->fresh());
            $this->setSuccess('Successfully upload signed document.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to upload signed document.', $error);
        }
        return $this->getModel();
    }

    /**
     * Remove signed document
     *
     * @return WorkContract|null
     */
    public function removeSignedDocument(): ?WorkContract
    {
        try {
            $workContract = $this->getModel();
            $workContract->clearMediaCollection('signed_document');
            $workContract->save();
            $this->setModel($workContract->fresh());
            $this->setSuccess('Successfully remove signed document.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to remove signed document.', $error);
        }
        return $this->getModel();
    }
}
