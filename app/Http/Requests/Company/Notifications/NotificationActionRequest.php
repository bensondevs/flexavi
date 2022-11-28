<?php

namespace App\Http\Requests\Company\Notifications;

use App\Models\Notification\Notification;
use Illuminate\Foundation\Http\FormRequest;

class NotificationActionRequest extends FormRequest
{
    /**
     * Notification model container
     *
     * @var Notification|null
     */
    private ?Notification $notification = null;

    /**
     * Get Notification based on supplied input
     *
     * @return Notification|null
     */
    public function getNotification(): ?Notification
    {
        if ($this->notification) {
            return $this->notification;
        }
        $id = $this->input('notification_id');

        return $this->notification = Notification::findOrFail($id);
    }

    /**
     * Get Notifiable from the current user
     *
     * @return mixed
     */
    public function getNotifiable(): mixed
    {
        return $this->user();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $gates = [
            'mark_read' => [
                'ability' => 'mark-read-notification',
            ],
            'mark_unread' => [
                'ability' => 'mark-unread-notification',
            ],
            'mark_all_read' => [
                'ability' => 'mark-read-all-notification',
            ],
            'mark_all_unread' => [
                'ability' => 'mark-read-all-notification',
            ],
        ];

        return $this->user()
            ->fresh()
            ->can($gates[explode('/', $this->path())[4]]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $requestUri = explode('/', $this->path())[4];
        if (!in_array($requestUri, ['mark_all_read', 'mark_all_unread'])) {
            return [
                'notification_id' => ['required', 'string', 'exists:notifications,id'],
            ];
        }

        return [];
    }
}
