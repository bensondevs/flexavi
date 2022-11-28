<?php

namespace Tests\Unit\Services\Notification\NotificationService;

use App\Models\Notification\Notification;
use App\Models\User\User;
use App\Services\Notification\NotificationService;
use Database\Factories\CompanyFactory;
use Database\Factories\EmployeeFactory;
use Database\Factories\OwnerFactory;
use Database\Factories\UserFactory;

/**
 * @see NotificationService::make()
 *      To the tested service class method.
 */
class MakeNotificationTest extends NotificationServiceTest
{
    /**
     * Ensure the notification is created and stored in the database
     *
     * @test
     * @return void
     */
    public function it_notification_should_created_and_stored_in_database(): void
    {
        $company = CompanyFactory::new()->create();
        $owner = OwnerFactory::new()->for($company)->create();
        $employee = EmployeeFactory::new()->for($company)->create();

        $notificationName = "employee.created" ;
        NotificationService::make($notificationName)
          ->by($owner->user)
          ->on($employee)
          ->extras($employee->load('user')->toArray())
          ->write();

        $this->assertDatabaseHas((new Notification())->getTable(), [
            'notification_name' => $notificationName,
            'actor_type' => User::class,
            'actor_id' => $owner->user->id,
            'object_type' => get_class($employee),
            'object_id' => $employee->id,
        ]);
    }

     /**
     * Ensure the notification is created and sent to owners of the company
     *
     * @test
     * @return void
     */
    public function it_notification_should_created_and_sent_to_owners_of_company(): void
    {
        $company = CompanyFactory::new()->create();
        $owners = [] ;
        for ($i=0; $i < 3; $i++) {
            array_push($owners, OwnerFactory::new()->for($company)->create());
        }
        $employee = EmployeeFactory::new()->for($company)->create();

        $actor = $owners[0]->user->fresh();

        $notificationName = "employee.created" ;
        NotificationService::make($notificationName)
          ->by($actor)
          ->on($employee)
          ->extras($employee->load('user')->toArray())
          ->writeToOwners();

        foreach ($owners as $owner) {
            $this->assertDatabaseHas((new Notification())->getTable(), [
                'company_id' => $actor->role_model->company_id,
                'notification_name' => $notificationName,
                'actor_type' => User::class,
                'actor_id' => $actor->id,
                'object_type' => get_class($employee),
                'object_id' => $employee->id,
                'notifier_type' => User::class,
                'notifier_id' => $owner->user->id,
            ]);
        }
    }

    /**
     * Ensure the notification is created and sent to specified user
     *
     * @test
     * @return void
     */
    public function it_notification_should_created_and_sent_to_specified_user(): void
    {
        $company = CompanyFactory::new()->create();
        $owner = OwnerFactory::new()->for($company)->create();
        $employee = EmployeeFactory::new()->for($company)->create();

        $actor = $owner->user;
        $receiver = UserFactory::new()->create();

        $notificationName = "employee.created" ;
        NotificationService::make($notificationName)
          ->by($actor)
          ->on($employee)
          ->extras($employee->load('user')->toArray())
          ->to($receiver)
          ->write();

        $this->assertDatabaseHas((new Notification())->getTable(), [
            'notification_name' => $notificationName,
            'actor_type' => User::class,
            'actor_id' => $owner->user->id,
            'object_type' => get_class($employee),
            'object_id' => $employee->id,
            'notifier_type' => User::class,
            'notifier_id' => $receiver->id,
        ]);
    }
}
