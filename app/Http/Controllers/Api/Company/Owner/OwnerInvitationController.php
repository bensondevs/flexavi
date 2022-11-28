<?php

namespace App\Http\Controllers\Api\Company\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\OwnerInvitations\{
    CancelOwnerInvitationRequest as CancelRequest,
    FindOwnerInvitationRequest as FindRequest,
    OwnerInvitationActionRequest as ActionRequest,
    InviteOwnerRequest as InviteRequest,
    PopulateOwnerInvitationRequest as PopulateRequest
};
use App\Http\Resources\Owner\OwnerInvitationResource;
use App\Repositories\Owner\OwnerInvitationRepository;
use Illuminate\Http\JsonResponse;

class OwnerInvitationController extends Controller
{
    /**
     * Owner invitation repository
     *
     * @var OwnerInvitationRepository
     */
    private OwnerInvitationRepository $ownerInvitationRepository;

    /**
     * Controller constructor method
     *
     * @param OwnerInvitationRepository $ownerInvitationRepository
     */
    public function __construct(OwnerInvitationRepository $ownerInvitationRepository)
    {
        $this->ownerInvitationRepository = $ownerInvitationRepository;
    }

    /**
     * Populate owner invitations
     *
     * @param PopulateRequest $request
     * @return JsonResponse
     */
    public function ownerInvitations(PopulateRequest $request): JsonResponse
    {
        $options = $request->options();
        $invitations = $this->ownerInvitationRepository->all($options, true);
        $invitations = OwnerInvitationResource::apiCollection($invitations);

        return response()->json([
            'invitations' => $invitations
        ]);
    }

    /**
     * View specific owner invitation
     *
     * @param FindRequest $request
     * @return JsonResponse
     */
    public function view(FindRequest $request): JsonResponse
    {
        $invitation = $request
            ->getInvitation()
            ->load($request->relations());

        return apiResponse($this->ownerInvitationRepository, [
            'invitation' => new OwnerInvitationResource($invitation),
        ]);
    }

    /**
     * Invite owner to register as user
     *
     * @param InviteRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Owner\OwnerInvitationTest::test_store_company_owner_invitation()
     *      To the controller method unit tester method.
     */
    public function store(InviteRequest $request): JsonResponse
    {
        $invitation = $this->ownerInvitationRepository->createAndSend($request->invitationData());

        return apiResponse($this->ownerInvitationRepository, [
            'invitation' => new OwnerInvitationResource($invitation->fresh()),
        ]);
    }

    /**
     * Cancel owner invitation
     *
     * @param CancelRequest $request
     * @return JsonResponse
     */
    public function cancel(CancelRequest $request): JsonResponse
    {
        $this->ownerInvitationRepository->setModel($request->getInvitation());
        $this->ownerInvitationRepository->cancel();

        return apiResponse($this->ownerInvitationRepository);
    }

    /**
     * Handle action from email
     *
     * @param ActionRequest $request
     * @return Response
     */
    public function accept(ActionRequest $request)
    {
        $this->ownerInvitationRepository->setModel($request->getInvitation());
        $this->ownerInvitationRepository->accept();

        return redirect('/');
    }
}
