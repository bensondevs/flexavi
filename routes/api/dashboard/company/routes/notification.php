<?php

use App\Http\Controllers\Api\Company\Notification\NotificationController;
use Illuminate\Support\Facades\Route;

/**
 * Notification routes group.
 *
 * @see \App\Http\Controllers\Api\Company\Notification\NotificationController
 *      To the routes group main controller.
 * @see \Tests\Feature\Dashboard\Company\Notification\NotificationTest
 *      To the routes group feature tester class.
 * @url /api/dashboard/companies/notifications
 */
Route::group(['prefix' => 'notifications'], function () {
    /**
     * Populate company notifications.
     *
     * @see \App\Http\Controllers\Api\Company\Notification\NotificationController::companyNotifications()
     *      To the route controller method.
     * @TODO Create feature test for this route.
     * @url /api/dashboard/companies/notifications/
     */
    Route::get('/', [NotificationController::class, 'companyNotifications']);

    /**
     * Populate notifiable notifications
     *
     * @see \App\Http\Controllers\Api\Company\Notification\NotificationController::notifiableNotifications()
     *      To the route controller method.
     * @see \Tests\Feature\Dashboard\Company\Notification\NotificationTest::test_populate_notifiable_notifications()
     *      To the route feature tester method.
     * @url /api/dashboard/companies/notifications/of_notifiable
     */
    Route::get('of_notifiable', [NotificationController::class, 'notifiableNotifications']);

    /**
     * Mark notification as read
     *
     * @deprecated
     * @see \App\Http\Controllers\Api\Company\Notification\NotificationController::markRead()
     *      To the route controller method.
     * @url /api/dashboard/companies/notifications/mark_read
     */
    Route::post('mark_read', [NotificationController::class, 'markRead']);

    /**
     * Mark notification as read
     *
     * @deprecated
     * @see \App\Http\Controllers\Api\Company\Notification\NotificationController::markUnread()
     *      To the route controller method.
     * @url /api/dashboard/companies/notifications/mark_unread
     */
    Route::post('mark_unread', [NotificationController::class, 'markUnread']);

    /**
     * Mark notification as read
     *
     * @see \App\Http\Controllers\Api\Company\Notification\NotificationController::markAllRead()
     *      To the route controller method.
     * @see \Tests\Feature\Dashboard\Company\Notification\NotificationTest::test_mark_all_notifications_as_read()
     *      To the route feature tester method.
     * @url /api/dashboard/companies/notifications/mark_all_read
     */
    Route::post('mark_all_read', [NotificationController::class, 'markAllRead']);

    /**
     * Mark all notifications as unread.
     *
     * @deprecated
     * @see \App\Http\Controllers\Api\Company\Notification\NotificationController::markAllUnread()
     *      To the route controller method.
     * @url /api/dashboard/companies/notifications/mark_all_unread
     */
    Route::post('mark_all_unread', [NotificationController::class, 'markAllUnread']);

    /**
     * Routes for notification count.
     *
     * @url /api/dashboard/companies/notifications/count/
     */
    Route::group(['prefix' => 'count'], function () {
        /**
         * Count company notifications.
         *
         * @see \App\Http\Controllers\Api\Company\Notification\NotificationController::countCompanyNotifications()
         *      To the route controller method.
         * @TODO Build feature test for this route.
         * @url /api/dashboard/companies/notifications/count/company
         */
        Route::get('company', [NotificationController::class, 'countCompanyNotifications']);

        /**
         * Count company notifications.
         *
         * @see \App\Http\Controllers\Api\Company\Notification\NotificationController::countCompanyNotifications()
         *      To the route controller method.
         * @see \Tests\Feature\Dashboard\Company\Notification\NotificationTest::test_count_notifiable_notifications()
         *      To the controller method feature tester method.
         * @url /api/dashboard/companies/notifications/count/company
         */
        Route::get('user', [NotificationController::class, 'countNotifiableNotifications']);
    });
});
