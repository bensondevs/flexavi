<?php

namespace App\Services\Setting\WorkContract;

use App\Enums\Setting\WorkContract\WorkContractContentPositionType;
use App\Models\Setting\WorkContractContentSetting;
use App\Models\Setting\WorkContractSetting;
use App\Models\WorkContract\WorkContract;
use App\Repositories\Setting\WorkContractContentSettingRepository;
use App\Repositories\Setting\WorkContractSettingRepository;

class WorkContractSettingService
{
    /**
     * Work contract setting repository container variables
     *
     * @var WorkContractSettingRepository
     */
    private WorkContractSettingRepository $workContractSettingRepository;

    /**
     * Work contract content setting repository container variables
     *
     * @var WorkContractContentSettingRepository
     */
    private WorkContractContentSettingRepository $workContractContentSettingRepository;


    /**
     * Service constructor method.
     *
     * @param WorkContractSettingRepository $workContractSettingRepository
     * @param WorkContractContentSettingRepository $workContractContentSettingRepository
     */
    public function __construct(
        WorkContractSettingRepository        $workContractSettingRepository,
        WorkContractContentSettingRepository $workContractContentSettingRepository,
    )
    {
        $this->workContractSettingRepository = $workContractSettingRepository;
        $this->workContractContentSettingRepository = $workContractContentSettingRepository;
    }

    /**
     * Set work contract as default format
     *
     * @param WorkContractSetting $workContractSetting
     * @param WorkContract|null $workContract
     * @param array $workContractData
     * @return WorkContractSettingRepository
     */
    public function setWorkContractAsDefaultFormat(WorkContractSetting $workContractSetting, WorkContract $workContract = null, array $workContractData = []): WorkContractSettingRepository
    {
        if ($workContract) {
            WorkContractContentSetting::where('work_contract_setting_id', $workContractSetting->id)->delete();
            $this->workContractSettingRepository->setModel($workContractSetting);
            $setting = $this->workContractSettingRepository->save([
                'footer' => $workContract->footer
            ]);
            $signature = $workContract->getFirstMedia('signature');
            $signature->copy($setting, 'signature');

            foreach ($workContract->contents as $content) {
                $this->workContractContentSettingRepository->destroyModel();
                $this->workContractContentSettingRepository->save([
                    'work_contract_setting_id' => $setting->id,
                    'text' => $content->text,
                    'position_type' => $content->position_type,
                    'text_type' => $content->text_type,
                    'order_index' => $content->order_index
                ]);
            }
        }

        if (is_null($workContract) && $workContractData) {
            WorkContractContentSetting::where('work_contract_setting_id', $workContractSetting->id)->delete();
            $this->workContractSettingRepository->setModel($workContractSetting);
            $setting = $this->workContractSettingRepository->save([
                'footer' => $workContractData['footer'],
                'signature' => $workContractData['signature'],
                'signature_name' => $workContractData['signature_name'],
            ]);


            foreach ($workContractData['foreword_contents'] as $content) {
                $this->workContractContentSettingRepository->destroyModel();
                $this->workContractContentSettingRepository->save([
                    'work_contract_setting_id' => $setting->id,
                    'text' => $content['text'],
                    'position_type' => WorkContractContentPositionType::Foreword,
                    'text_type' => $content['text_type'],
                    'order_index' => $content['order_index']
                ]);
            }

            foreach ($workContractData['contract_contents'] as $content) {
                $this->workContractContentSettingRepository->destroyModel();
                $this->workContractContentSettingRepository->save([
                    'work_contract_setting_id' => $setting->id,
                    'text' => $content['text'],
                    'position_type' => WorkContractContentPositionType::Foreword,
                    'text_type' => $content['text_type'],
                    'order_index' => $content['order_index']
                ]);
            }
        }
        return $this->workContractSettingRepository;
    }

    /**
     * Save work contract company setting
     *
     * @param WorkContractSetting $workContractSetting
     * @param array $data
     * @return WorkContractSettingRepository
     */
    public function save(WorkContractSetting $workContractSetting, array $data): WorkContractSettingRepository
    {
        WorkContractContentSetting::where('work_contract_setting_id', $workContractSetting->id)->delete();
        $this->workContractSettingRepository->setModel($workContractSetting);
        $setting = $this->workContractSettingRepository->save($data['work_contract_data']);

        foreach ($data['work_contract_forewords_data'] as $content) {
            $this->workContractContentSettingRepository->destroyModel();
            $content['work_contract_setting_id'] = $setting->id;
            $content['position_type'] = WorkContractContentPositionType::Foreword;
            $this->workContractContentSettingRepository->save($content);
        }

        foreach ($data['work_contract_forewords_data'] as $content) {
            $this->workContractContentSettingRepository->destroyModel();
            $content['work_contract_setting_id'] = $setting->id;
            $content['position_type'] = WorkContractContentPositionType::Contract;
            $this->workContractContentSettingRepository->save($content);
        }
        return $this->workContractSettingRepository;
    }
}
