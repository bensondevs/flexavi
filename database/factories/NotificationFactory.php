<?php

namespace Database\Factories;

use App\Enums\Notification\NotificationType;
use App\Models\Company\Company;
use App\Models\Notification\Notification;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        return $this->afterMaking(function (Notification $notification) {
            if (!$notification->company_id) {
                $company = Company::factory()->create();
                $notification->company_id = $company->id;
            }

            if (!$notification->actor_type) {
                $user = User::factory()->create();
                $notification->actor()->associate($user);
            }

            if (!$notification->notifier_type) {
                $user = User::factory()->create();
                $notification->notifier()->associate($user);
            }
        });
    }

    public function definition(): array
    {
        return [
            'notification_name' => 'employee.created',
            'type' => NotificationType::getRandomValue(),
            'extras' => json_encode($this->faker->words(2)),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    /**
     * Set notification type
     *
     * @param int $type
     * @return Factory
     */
    public function type(int $type): Factory
    {
        return $this->state(function (array $attributes) use ($type) {
            return [
                'type' => $type,
            ];
        });
    }

    /**
     * Indicate that the model's prime owner.
     *
     * @return Factory
     */
    public function read(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'read_at' => now(),
            ];
        });
    }

    /**
     * Indicate that the model's prime owner.
     *
     * @return Factory
     */
    public function unread(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'read_at' => null,
            ];
        });
    }
}
