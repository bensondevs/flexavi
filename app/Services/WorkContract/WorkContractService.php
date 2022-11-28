<?php

namespace App\Services\WorkContract;

use App\Enums\Setting\WorkContract\WorkContractContentPositionType;
use App\Jobs\SendMail;
use App\Mail\WorkContract\WorkContractMail;
use App\Models\Setting\WorkContractSetting;
use App\Models\WorkContract\WorkContract;
use App\Models\WorkContract\WorkContractContent;
use App\Models\WorkService\WorkService;
use App\Repositories\WorkContract\WorkContractContentRepository;
use App\Repositories\WorkContract\WorkContractRepository;
use App\Repositories\WorkContract\WorkContractServiceRepository;
use App\Services\Template\WorkContract\WorkContractTemplateService;
use DB;
use PHPStan\DependencyInjection\ParameterNotFoundException;
use ReflectionException;

class WorkContractService
{
    /**
     * Work contract repository
     *
     * @var WorkContractRepository
     */
    private WorkContractRepository $workContractRepository;

    /**
     * Work contract content repository
     *
     * @var WorkContractContentRepository
     */
    private WorkContractContentRepository $workContractContentRepository;

    /**
     * WorkContractService repository
     *
     * @param WorkContractServiceRepository $workContractServiceRepository
     */
    private WorkContractServiceRepository $workContractServiceRepository;


    /**
     * WorkContractService constructor.
     *
     *
     * @param WorkContractRepository $workContractRepository
     * @param WorkContractContentRepository $workContractContentRepository
     * @param WorkContractServiceRepository $workContractServiceRepository
     */
    public function __construct(
        WorkContractRepository        $workContractRepository,
        WorkContractContentRepository $workContractContentRepository,
        WorkContractServiceRepository $workContractServiceRepository,
    )
    {
        $this->workContractContentRepository = $workContractContentRepository;
        $this->workContractRepository = $workContractRepository;
        $this->workContractServiceRepository = $workContractServiceRepository;
    }

    /**
     * Format content with templating service
     *
     * @param WorkContract $workContract
     * @param string $content
     * @return string
     */
    public function formatContentWithTemplatingService(WorkContract $workContract, string $content): string
    {
        $service = new WorkContractTemplateService($workContract);
        try {
            return $service->initialize(
                customerId: $workContract->customer_id,
                workContractId: $workContract->id,
                companyId: $workContract->company_id,
            )->setContent($content)->execute()->render();
        } catch (ParameterNotFoundException|ReflectionException $e) {
            return "Failed to render log. " . $e->getMessage();
        }
    }

    /**
     * Resend work contract
     *
     * @param WorkContract $workContract
     * @return WorkContractRepository
     */
    public function resend(WorkContract $workContract): WorkContractRepository
    {
        DB::beginTransaction();
        try {
            $this->workContractRepository->setModel($workContract);
            $this->workContractRepository->send();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->workContractRepository->setError("Failed to resend work contract. " . $e->getMessage());
        }

        return $this->workContractRepository;
    }

    /**
     * Delete work contract
     *
     * @param WorkContract $workContract
     * @return WorkContractRepository
     */
    public function useCompanyFormat(WorkContract $workContract): WorkContractRepository
    {
        DB::beginTransaction();
        try {
            $workContractSetting = WorkContractSetting::where('company_id', $workContract->company_id)->first();
            if (!$workContractSetting) {
                $this->workContractRepository->setError("Failed to use company format. Company setting not found.");
                return $this->workContractRepository;
            }

            WorkContractContent::where('work_contract_id', $workContract->id)->delete();
            foreach ($workContractSetting->contents as $content) {
                $this->workContractContentRepository->destroyModel();
                $this->workContractContentRepository->save([
                    'work_contract_id' => $workContract->id,
                    'position_type' => $content->position_type,
                    'text' => $content->text,
                    'order_index' => $content->order_index,
                    'text_type' => $content->text_type,
                ]);
            }
            $this->workContractRepository->setModel($workContract);
            $workContract = $this->workContractRepository->save(['footer' => $workContractSetting->footer, 'company_id' => $workContract->company_id]);
            $workContract->clearMediaCollection('signature');
            $signature = $workContractSetting->getFirstMedia('signature');
            $signature->copy($workContract, 'signature');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->workContractRepository->setError("Failed to use company format. " . $e->getMessage());
        }
        return $this->workContractRepository;
    }

    /**
     * Save work contract
     *
     * @param WorkContract|null $workContract
     * @param array $workContractData
     * @return WorkContractRepository
     */
    public function save(WorkContract $workContract = null, array $workContractData = []): WorkContractRepository
    {
        DB::beginTransaction();
        try {
            if ($workContract) {
                \App\Models\WorkContract\WorkContractService::where('work_contract_id', $workContract->id)->delete();
                WorkContractContent::where('work_contract_id', $workContract->id)->delete();
                $this->workContractRepository->setModel($workContract);
            }

            $workContract = $this->workContractRepository->save($workContractData['work_contract_data']);

            // Save work contract foreword content
            foreach ($workContractData['work_contract_forewords_data'] as $foreword) {
                $this->workContractContentRepository->destroyModel();
                $this->workContractContentRepository->save([
                    'work_contract_id' => $workContract->id,
                    'position_type' => WorkContractContentPositionType::Foreword,
                    'text' => $foreword['text'],
                    'order_index' => $foreword['order_index'],
                    'text_type' => $foreword['text_type'],
                ]);
            }

            // Save work contract content
            foreach ($workContractData['work_contract_contracts_data'] as $contract) {
                $this->workContractContentRepository->destroyModel();
                $this->workContractContentRepository->save([
                    'work_contract_id' => $workContract->id,
                    'position_type' => WorkContractContentPositionType::Contract,
                    'text' => $contract['text'],
                    'order_index' => $contract['order_index'],
                    'text_type' => $contract['text_type'],
                ]);
            }

            if ($workContract->isSent() && count($workContractData['items_data']) === 0) {
                throw new \Exception("Failed to save work contract. Work contract must have at least one item.");
            }

            // Save work contract services
            foreach ($workContractData['items_data'] as $workService) {
                $this->workContractServiceRepository->destroyModel();
                $workServiceData = WorkService::find($workService['work_service_id']);
                $this->workContractServiceRepository->save([
                    'work_contract_id' => $workContract->id,
                    'tax_percentage' => $workServiceData->tax_percentage,
                    'work_service_id' => $workService['work_service_id'],
                    'unit_price' => $workServiceData->price,
                    'amount' => $workService['amount'],
                    'total' => $workServiceData->price * $workService['amount'],
                ]);
            }

            $workContract->countWorksAmount();


            if ($workContract->isSent()) {
                $this->sendWorkContract($workContract);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->workContractRepository->setError("Failed to save work contract. " . $e->getMessage());
        }

        return $this->workContractRepository;
    }

    /**
     * Send work contract to customer
     *
     * @param WorkContract $workContract
     * @return void
     */
    public static function sendWorkContract(WorkContract $workContract): void
    {
        $mailable = new WorkContractMail($workContract);
        $job = new SendMail($mailable, $workContract->customer->email);
        dispatch($job);
    }
}
