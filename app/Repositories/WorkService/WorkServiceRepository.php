<?php

namespace App\Repositories\WorkService;

use App\Enums\WorkService\WorkServiceStatus;
use App\Models\WorkService\WorkService;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class WorkServiceRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new WorkService());
    }

    /**
     * Save work service.
     *
     * @param int $status
     * @return WorkService|null
     */
    public function changeStatus(int $status): ?WorkService
    {
        try {
            $workService = $this->getModel();
            $workService->status = $status;
            $workService->save();
            $this->setModel($workService->fresh());
            $this->setSuccess('Successfully changed work service status.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to change work service status. ' . $error);
        }
        return $this->getModel();
    }

    /**
     * Save work service.
     *
     * @param array $workServiceData
     * @return WorkService|null
     */
    public function save(array $workServiceData): ?WorkService
    {
        try {
            $workService = $this->getModel();
            $workService->fill($workServiceData);
            $workService->save();
            $this->setModel($workService->fresh());
            $this->setSuccess('Successfully save work service data.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save work service data.', $error);
        }

        return $this->getModel();
    }

    /**
     * Delete record of the model set in repository
     *
     * @param bool $force
     * @return bool
     */
    public function delete(bool $force = false): bool
    {
        try {
            $workService = $this->getModel();
            $force ? $workService->forceDelete() : $workService->delete();
            $this->destroyModel();
            $this->setSuccess('Successfully delete work service.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to delete work.', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Restore work service from trash
     *
     * @return WorkService|null
     */
    public function restore(): ?WorkService
    {
        try {
            $workService = $this->getModel();
            $workService->restore();
            $this->setModel($workService);
            $this->setSuccess('Successfully restore work service.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to restore work.', $error);
        }

        return $this->getModel();
    }

    /**
     * Populate active work service
     *
     * @param array $options
     * @return Collection|LengthAwarePaginator
     */
    public function active(array $options = []): LengthAwarePaginator|Collection
    {
        $options['wheres'][] = [
            'column' => 'status',
            'value' => WorkServiceStatus::Active,
        ];

        return $this->all($options);
    }

    /**
     * Populate inactive work service
     *
     * @param array $options
     * @return Collection|LengthAwarePaginator
     */
    public function inactive(array $options = []): LengthAwarePaginator|Collection
    {
        $options['wheres'][] = [
            'column' => 'status',
            'value' => WorkServiceStatus::Inactive,
        ];

        return $this->all($options);
    }
}
