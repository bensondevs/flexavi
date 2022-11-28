<?php

namespace App\Http\Controllers\Api\Auths;

use App\Http\Controllers\Controller;
use App\Repositories\{Address\AddressRepository,
    Auths\AuthRepository,
    Company\CompanyOwnerRepository,
    Invitation\RegisterInvitationRepository
};
use App\Services\Auth\RegisterService;
use App\Services\Log\LogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Auth repository class container
     *
     * @var AuthRepository
     */
    private AuthRepository $authRepository;

    /**
     * Owner repository class container
     *
     * @var CompanyOwnerRepository
     */
    private CompanyOwnerRepository $companyOwnerRepository;

    /**
     * Address repository class container
     *
     * @var AddressRepository
     */
    private AddressRepository $addressRepository;

    /**
     * Register Invitation class container
     *
     * @var RegisterInvitationRepository
     */
    private RegisterInvitationRepository $registerInvitationRepository;

    /**
     * Register service class container variable
     *
     * @var RegisterService
     */
    private RegisterService $registerService;

    /**
     * Controller constructor method
     *
     * @param AuthRepository $authRepository
     * @param CompanyOwnerRepository $companyOwnerRepository
     * @param AddressRepository $addressRepository
     * @param RegisterInvitationRepository $registerInvitationRepository
     * @param RegisterService $registerService
     */
    public function __construct(
        AuthRepository               $authRepository,
        CompanyOwnerRepository       $companyOwnerRepository,
        AddressRepository            $addressRepository,
        RegisterInvitationRepository $registerInvitationRepository,
        RegisterService              $registerService
    )
    {
        $this->authRepository = $authRepository;
        $this->companyOwnerRepository = $companyOwnerRepository;
        $this->addressRepository = $addressRepository;
        $this->registerInvitationRepository = $registerInvitationRepository;
        $this->registerService = $registerService;
    }


    /**
     * Verify email by sending code given through email confirmation
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function verifyEmail(Request $request): RedirectResponse
    {
        $code = $request->input('code');
        $this->authRepository->verifyEmail($code);
        return redirect()->away(config('app.frontend_url'));
    }

    /**
     * Attempt logout for requesting user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $this->authRepository->getModel();

        $this->authRepository->setModel($request->user());
        $this->authRepository->logout();

        LogService::make("user.logout")->by($user)->on($user)->write();

        return apiResponse($this->authRepository);
    }

}
