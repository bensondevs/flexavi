<?php

namespace App\Http\Controllers\Api\Company\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\OwnerInvitations\PopulateOwnerPermissionsRequest as PopulateRequest;
use App\Http\Resources\Permission\ModuleResource;
use App\Repositories\Permission\ModuleRepository;
use Illuminate\Http\JsonResponse;

class OwnerInvitationPermissionController extends Controller
{
    /**
     * Module repository container variable
     *
     * @var ModuleRepository
     */
    private ModuleRepository $moduleRepository;

    /**
     * Controller constructor method
     *
     * @param ModuleRepository $moduleRepository
     */
    public function __construct(ModuleRepository $moduleRepository)
    {
        $this->moduleRepository = $moduleRepository;
    }

    /**
     * Populate owner permissions
     *
     * @param PopulateRequest $request
     * @return JsonResponse
     */
    public function permissions(PopulateRequest $request): JsonResponse
    {
        $modules = $this->moduleRepository->all($request->options());
        $modules = ModuleResource::collection($modules);
        return response()->json(['modules' => $modules]);
    }
}
