<?php

namespace App\Http\Controllers\Api\Company\Notification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Notifications\{
    NotificationActionRequest as ActionRequest,
    PopulateNotificationsRequest as PopulateRequest
};
use App\Repositories\Notification\NotificationRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @see \Tests\Feature\Dashboard\Company\Notification\NotificationTest
 *      To the controller class unit tester class.
 */
class NotificationController extends Controller
{
    /**
     * Repository Container
     *
     * @var NotificationRepository
     */
    private NotificationRepository $notificationRepository;

    /**
     * Create New Controller Instance
     *
     * @return void
     */
    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * Populate company notifications
     *
     * @TODO Create feature test.
     * @param PopulateRequest $request
     * @return JsonResponse
     * @url /api/dashboard/companies/notifications/
     */
    public function companyNotifications(PopulateRequest $request): JsonResponse
    {
        $options = $request->companyOptions();
        $notifications = $this->notificationRepository->all($options, true);
        $notifications = $this->notificationRepository->groupByDate();

        return response()->json(compact('notifications'));
    }

    /**
     * Populate notifiable notifications
     *
     * @param PopulateRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Notification\NotificationTest::test_populate_notifiable_notifications()
     *      To the controller method unit tester method.
     * @url /api/dashboard/companies/notifications/of_notifiable
     */
    public function notifiableNotifications(PopulateRequest $request): JsonResponse
    {
        $options = $request->notifierOptions();
        $notifications = $this->notificationRepository->all($options, true);
        $notifications = $this->notificationRepository->groupByDate();

        return response()->json(compact('notifications'));
    }

    /**
     * Count company notifications
     *
     * @param Request $request
     * @return JsonResponse
     * @url /api/dashboard/companies/notifications/count/company
     */
    public function countCompanyNotifications(Request $request): JsonResponse
    {
        $notification = $this->notificationRepository->count([
            'company_id' => $request->user()->company->id,
            'unread' => true,
        ]);

        return response()->json(compact('notification'));
    }

    /**
     * Count notifiable notifications
     *
     * @param Request $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Notification\NotificationTest::test_count_notifiable_notifications()
     *      To the controller method unit tester method.
     * @url /api/dashboard/companies/notifications/count/user
     */
    public function countNotifiableNotifications(Request $request): JsonResponse
    {
        $user = $request->user();
        $notification = $this->notificationRepository->count([
            'notifier_type' => get_class($user),
            'notifier_id' => $user->id,
        ]);
        return response()->json(compact('notification'));
    }

    /**
     * Mark notification as read
     *
     * @param ActionRequest $request
     * @return JsonResponse
     * @url /api/dashboard/companies/notifications/mark_read
     * @deprecated
     */
    public function markRead(ActionRequest $request): JsonResponse
    {
        $notification = $request->getNotification();
        $this->notificationRepository->setModel($notification);
        $this->notificationRepository->markRead();
        return apiResponse($this->notificationRepository);
    }

    /**
     * Mark notification as unread
     *
     * @param ActionRequest $request
     * @return JsonResponse
     * @url /api/dashboard/companies/notifications/mark_unread
     * @deprecated
     */
    public function markUnread(ActionRequest $request): JsonResponse
    {
        $notification = $request->getNotification();
        $this->notificationRepository->setModel($notification);
        $this->notificationRepository->markUnread();
        return apiResponse($this->notificationRepository);
    }

    /**
     * Mark all notifications as read
     *
     * @param ActionRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Notification\NotificationTest::test_mark_all_notifications_as_read()
     *      To the controller method unit tester method.
     * @url /api/dashboard/companies/notifications/mark_all_read
     */
    public function markAllRead(ActionRequest $request): JsonResponse
    {
        $notifiable = $request->getNotifiable();
        $this->notificationRepository->markAllRead($notifiable);
        return apiResponse($this->notificationRepository);
    }

    /**
     * Mark all notifications as unread
     *
     * @param ActionRequest $request
     * @return JsonResponse
     * @url /api/dashboard/companies/notifications/mark_all_unread
     * @deprecated
     */
    public function markAllUnread(ActionRequest $request): JsonResponse
    {
        $notifiable = $request->getNotifiable();
        $this->notificationRepository->markAllUnread($notifiable);
        return apiResponse($this->notificationRepository);
    }
}
