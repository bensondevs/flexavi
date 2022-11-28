<?php

namespace App\Repositories\Notification;

use App\Http\Resources\Notification\NotificationResource;
use App\Models\Notification\Notification;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class NotificationRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new Notification());
    }

    /**
     * Group notifications by date
     */
    public function groupByDate(): LengthAwarePaginator|array|Collection
    {
        $groupByCreatedAt = fn($notification) => carbon()
            ->parse($notification->created_at ?? $notification['created_at'])->toDateString();
        if ($pagination = $this->getPagination()) {
            $pagination = NotificationResource::apiCollection($pagination);
            $paginationData = $pagination instanceof LengthAwarePaginator ? $pagination->toArray()['data'] : $pagination['data'];
            $pagination = collect($pagination)->replace([
                'data' => collect(NotificationResource::collection($paginationData))
                    ->groupBy($groupByCreatedAt)->toArray()
            ]);
            $this->setCollection(collect($pagination['data']));
            return $this->setPagination($pagination);
        }
        $collection = $this->getCollection();
        $collection = $collection->groupBy($groupByCreatedAt);
        return $this->setCollection($collection);
    }

    /**
     * Count notifications
     */
    public function count(array $options): array
    {
        $notification = $this->getModel();
        $notification = $notification->where(function (Builder $query) use ($options) {
            $query->when(
                isset($options['company_id']),
                fn($q) => $q->where('company_id', $options['company_id'])
            );
            $query->when(
                isset($options['notifier_type']),
                fn($q) => $q->where('notifier_type', $options['notifier_type'])
            );
            $query->when(
                isset($options['notifier_id']),
                fn($q) => $q->where('notifier_id', $options['notifier_id'])
            );
            $query->when(
                isset($options['unread']),
                fn($q) => $q->whereNull('read_at')
            );
        });
        $all = $notification->count();
        $unread = $notification->whereNull('read_at')->count();
        $read = $all - $unread;
        return [
            'all' => $all,
            'read' => $read,
            'unread' => $unread
        ];
    }

    /**
     * Mark notification as read
     *
     * @return void
     */
    public function markRead(): void
    {
        try {
            $item = $this->getModel();
            $item->read_at = now();
            $item->save();
            $this->setSuccess('Successfully mark notification as read.');
        } catch (QueryException $qe) {
            $this->setError('Failed to mark as read notification.');
        }
    }

    /**
     * Mark notification as unread
     *
     * @return void
     */
    public function markUnread(): void
    {
        try {
            $item = $this->getModel();
            $item->read_at = null;
            $item->save();
            $this->setSuccess('Successfully mark notification as unread.');
        } catch (QueryException $qe) {
            $this->setError(
                'Failed to mark notification as unread notification.'
            );
        }
    }

    /**
     * Mark all notifications as read
     *
     * @param mixed $notifier
     * @return void
     */
    public function markAllRead(mixed $notifier): void
    {
        try {
            Notification::where('notifier_type', get_class($notifier))
                ->where('notifier_id', $notifier->id)
                ->update(['read_at' => now()]);
            $this->setSuccess('Successfully mark all notification as read.');
        } catch (QueryException $qe) {
            $this->setError('Failed to mark as read notification.');
        }
    }

    /**
     * Mark all notifications as unread
     *
     * @param mixed $notifier
     * @return void
     */
    public function markAllUnread(mixed $notifier): void
    {
        try {
            Notification::where('notifier_type', get_class($notifier))
                ->where('notifier_id', $notifier->id)
                ->update(['read_at' => null]);
            $this->setSuccess('Successfully mark all notification as unread.');
        } catch (QueryException $qe) {
            $this->setError(
                'Failed to mark notification as unread notification.'
            );
        }
    }
}
