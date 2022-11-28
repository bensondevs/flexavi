<?php

namespace App\Repositories\Invoice;

use App\Http\Resources\Invoice\InvoiceLogResource;
use App\Models\Invoice\InvoiceLog;
use App\Repositories\Base\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class InvoiceLogRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new InvoiceLog());
    }

    /**
     * Group notifications by date
     */
    public function groupByDate(): LengthAwarePaginator|array|Collection
    {
        $groupByCreatedAt = fn($log) => carbon()
            ->parse($log->created_at ?? $log['created_at'])->toDateString();
        if ($pagination = $this->getPagination()) {
            $pagination = InvoiceLogResource::apiCollection($pagination);
            $paginationData = $pagination instanceof LengthAwarePaginator ? $pagination->toArray()['data'] : $pagination['data'];
            $pagination = collect($pagination)->replace([
                'data' =>
                    collect($paginationData)->groupBy($groupByCreatedAt)->toArray()
            ]);
            $this->setCollection(collect($pagination['data']));
            return $this->setPagination($pagination);
        }
        $collection = $this->getCollection();
        $collection = $collection->groupBy($groupByCreatedAt);
        return $this->setCollection($collection);
    }

}
