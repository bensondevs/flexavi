<?php

namespace App\Http\Controllers\Api\Company\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\EmployeeInvitations\CancelEmployeeInvitationRequest as CancelRequest;
use App\Http\Requests\Company\EmployeeInvitations\FindEmployeeInvitationRequest as FindRequest;
use App\Http\Requests\Company\EmployeeInvitations\HandleActionRequest as ActionRequest;
use App\Http\Requests\Company\EmployeeInvitations\InviteEmployeeRequest as InviteRequest;
use App\Http\Requests\Company\EmployeeInvitations\PopulateEmployeeInvitationRequest as PopulateRequest;
use App\Http\Resources\Employee\EmployeeInvitationResource;
use App\Repositories\Employee\EmployeeInvitationRepository;
use App\Services\Employee\EmployeeInvitationService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @see \Tests\Feature\Dashboard\Company\Employee\EmployeeInvitationTest
 *      To the controller class unit tester class.
 */
class EmployeeInvitationController extends Controller
{
    /**
     * Employee invitation service class container.
     *
     * @var EmployeeInvitationService
     */
    private EmployeeInvitationService $employeeInvitationService;

    /**
     * Employee Invitation Repository Class Container
     *
     * @var EmployeeInvitationRepository
     */
    private EmployeeInvitationRepository $employeeInvitationRepository;

    /**
     * EmployeeInvitationRepository constructor
     *
     * @param EmployeeInvitationService $employeeInvitationService
     * @param EmployeeInvitationRepository $employeeInvitationRepository
     */
    public function __construct(
        EmployeeInvitationService $employeeInvitationService,
        EmployeeInvitationRepository $employeeInvitationRepository,
    ) {
        $this->employeeInvitationService = $employeeInvitationService;
        $this->employeeInvitationRepository = $employeeInvitationRepository;
    }

    /**
     * Handle action from email
     *
     * @param ActionRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function handleAction(ActionRequest $request): Application|RedirectResponse|Redirector
    {
        $this->employeeInvitationRepository
            ->setModel($request->getInvitation());
        $this->employeeInvitationRepository
            ->setUsed();
        $this->employeeInvitationRepository
            ->handleInvitationFulfilled();

        return redirect('/');
    }

    /**
     * Populate employee invitations
     *
     * @param PopulateRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Employee\EmployeeInvitationTest::test_populate_company_employee_invitations()
     *      To the controller method unit tester method.
     */
    public function employeeInvitations(PopulateRequest $request): JsonResponse
    {
        $options = $request->options();
        $invitations = $this->employeeInvitationRepository->all($options);
        $invitations = $this->employeeInvitationRepository->paginate($options['per_page']);

        return response()->json([
            'invitations' => EmployeeInvitationResource::apiCollection($invitations),
        ]);
    }

    /**
     * View specific employee invitation
     *
     * @param FindRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Employee\EmployeeInvitationTest::test_get_company_employee_invitation()
     *      To the controller method unit tester method.
     */
    public function view(FindRequest $request): JsonResponse
    {
        $invitation = $request
            ->getEmployeeInvitation()
            ->load($request->relations());

        return apiResponse($this->employeeInvitationRepository, [
            'invitation' => new EmployeeInvitationResource($invitation),
        ]);
    }

    /**
     * Invite employee to register as user
     *
     * @param InviteRequest $request
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @see \Tests\Feature\Dashboard\Company\Employee\EmployeeInvitationTest::test_store_company_employee_invitation()
     * @see \Tests\Integration\Dashboard\Company\Employee\EmployeeInvitation\EmployeeInvitationStoreTest
     *      To the method unit test method and integration test class
     */
    public function store(InviteRequest $request): JsonResponse
    {
        $employeeInvitationRepository = $this->employeeInvitationService->createAndSendInvitation(
            $request->invitationData()
        );
        $invitation = $employeeInvitationRepository->getModel();

        return apiResponse($employeeInvitationRepository, [
            'invitation' => new EmployeeInvitationResource($invitation),
        ]);
    }

    /**
     * Cancel employee invitation
     *
     * @param CancelRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Employee\EmployeeInvitationTest::test_cancel_company_employee_invitation()
     *      To the controller method unit tester method.
     */
    public function cancel(CancelRequest $request): JsonResponse
    {
        $this->employeeInvitationRepository->setModel($request->getInvitation());
        $this->employeeInvitationRepository->cancel();

        return apiResponse($this->employeeInvitationRepository);
    }

    /**
     *  Accept an invitation from email
     *
     * @param ActionRequest $request
     * @return Response
     */
    public function accept(ActionRequest $request)
    {
        $this->employeeInvitationRepository->setModel($request->getInvitation());
        $this->employeeInvitationRepository->accept();

        return redirect('/');
    }
}
