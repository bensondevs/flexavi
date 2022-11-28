<?php

namespace App\Services\Auth;

use App\Repositories\Address\AddressRepository;
use App\Repositories\Auths\AuthRepository;
use App\Repositories\Company\CompanyOwnerRepository;
use App\Repositories\Invitation\RegisterInvitationRepository;
use App\Repositories\Permission\PermissionRepository;
use App\Repositories\User\UserSocialiteAccountRepository;
use App\Services\Company\CompanyService;

/**
 * @see \Tests\Unit\Services\Auth\RegisterService\RegisterServiceTest
 *      To the service class unit tester class.
 */
class RegisterService
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
     * User socialite account class container
     *
     * @var UserSocialiteAccountRepository
     */
    private UserSocialiteAccountRepository $userSocialiteAccountRepository;

    /**
     * Company service class container
     *
     * @var CompanyService
     */
    private CompanyService $companyService;

    /**
     * Service constructor method
     *
     * @param AuthRepository $authRepository
     * @param CompanyOwnerRepository $companyOwnerRepository
     * @param AddressRepository $addressRepository
     * @param RegisterInvitationRepository $registerInvitationRepository
     * @param UserSocialiteAccountRepository $userSocialiteAccountRepository
     * @param CompanyService $companyService
     */
    public function __construct(
        AuthRepository                 $authRepository,
        CompanyOwnerRepository         $companyOwnerRepository,
        AddressRepository              $addressRepository,
        RegisterInvitationRepository   $registerInvitationRepository,
        UserSocialiteAccountRepository $userSocialiteAccountRepository,
        CompanyService                 $companyService
    )
    {
        $this->authRepository = $authRepository;
        $this->companyOwnerRepository = $companyOwnerRepository;
        $this->addressRepository = $addressRepository;
        $this->registerInvitationRepository = $registerInvitationRepository;
        $this->userSocialiteAccountRepository = $userSocialiteAccountRepository;
        $this->companyService = $companyService;
    }

    /**
     * Handle register service
     *
     * @param array $data
     * @return AuthRepository
     * @see \Tests\Unit\Services\Auth\RegisterService\HandleTest
     *      To the method unit tester class.
     */
    public function handle(array $data): AuthRepository
    {
        $userData = $data['user'];
        $addressData = $data['address'];
        $invitation = $data['invitation'] ?? [];

        $user = $this->authRepository->register($userData);
        $user = $user->fresh();

        // Owner register directly
        // Will be main/prime owner
        if (!$invitation) {
            $this->companyOwnerRepository->assignUser($user);

            // Set all permissions to the main owner user
            $user->syncPermissions(app(PermissionRepository::class)->permissionNames());
        }

        // Owner registered through invitation
        // Will not be main/prime owner -- just ordinary owner
        if ($invitation) {
            $this->registerInvitationRepository->setModel($invitation);
            $this->registerInvitationRepository->handleInvitationFulfilled();
        }

        if (isset($data['socialite'])) {
            $socialite = $data['socialite'];
            $socialite['user_id'] = $user->id;
            $this->userSocialiteAccountRepository->save($socialite);
        }

        if ($user->isOwner() && !$invitation) {
            $company = $this->companyService->ownerRegistered($user);
            $this->companyOwnerRepository->setModel($user->owner);
            $this->companyOwnerRepository->assignCompany($company);
        }

        $this->addressRepository->setAddressable($user->role_model);
        $this->addressRepository->save($addressData);
        return $this->authRepository;
    }
}
