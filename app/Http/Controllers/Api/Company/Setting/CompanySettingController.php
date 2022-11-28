<?php

namespace App\Http\Controllers\Api\Company\Setting;

use App\Enums\Setting\SettingModule;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Settings\PopulateSettingRequest as PopulateRequest;
use App\Http\Requests\Company\Settings\UpdateCompanySettingRequest as UpdateRequest;
use App\Models\Setting\CompanySetting;
use App\Repositories\Setting\GeneralSettingRepository;
use App\Services\Setting\SettingService;
use Illuminate\Http\JsonResponse;

class CompanySettingController extends Controller
{
    /**
     * General Setting Repository Class Container
     *
     * @var GeneralSettingRepository
     */
    private GeneralSettingRepository $settingRepository;

    /**
     * Controller constructor method.
     *
     */
    public function __construct()
    {
        $this->settingRepository = new GeneralSettingRepository(CompanySetting::class);
    }

    /**
     * View dashboard setting
     *
     * @param PopulateRequest $request
     * @return JsonResponse
     */
    public function view(PopulateRequest $request): JsonResponse
    {
        $setting = SettingService::find($request->getCompany(), SettingModule::Company);
        return response()->json(['setting' => $setting]);
    }

    /**
     * Update setting
     *
     * @param UpdateRequest $request
     * @return JsonResponse
     */
    public function update(UpdateRequest $request): JsonResponse
    {
        $this->settingRepository->setModel($request->getSetting());
        $data = array_merge(
            $request->validated(),
            ['company_id' => $request->getCompany()->id]
        );
        $setting = $this->settingRepository->save($data);
        return apiResponse($this->settingRepository, compact('setting'));
    }
}
