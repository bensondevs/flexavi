<?php

namespace App\Repositories\ExecuteWork;

use App\Enums\ExecuteWorkPhoto\PhotoConditionType as Type;
use App\Jobs\ExecuteWorkPhoto\UploadMultiplePhoto;
use App\Models\ExecuteWork\ExecuteWork;
use App\Models\ExecuteWork\ExecuteWorkPhoto;
use App\Models\ExecuteWork\WorkWarranty;
use App\Models\WorkService\WorkService;
use App\Repositories\Base\BaseRepository;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ExecuteWorkPhotoRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new ExecuteWorkPhoto());
    }

    /**
     * Populate before-work photos
     *
     * @return Collection|LengthAwarePaginator
     */
    public function beforeWorkPhotos()
    {
        $photos = $this->getCollection();
        $type = Type::Before;

        return $photos->where('photo_condition_type', $type)->values();
    }

    /**
     * Populate after-work photos
     *
     * @return Collection|LengthAwarePaginator
     */
    public function afterWorkPhotos()
    {
        $photos = $this->getCollection();
        $type = Type::After;

        return $photos->where('photo_condition_type', $type)->values();
    }

    /**
     * Upload photo for execute work
     *
     * @param  array  $photoData
     * @return ExecuteWorkPhoto|null
     */
    public function uploadPhoto(array $photoData = [])
    {
        try {
            $photo = $this->getModel();
            $photo->photo = $photoData['photo'];
            unset($photoData['photo']);
            $photo->fill($photoData);
            $photo->save();
            $this->setModel($photo);
            $this->setSuccess('Successfully upload execute work photo.');
        } catch (QueryException $qe) {
            $this->setError('Failed to upload execute work photo.');
        }

        return $this->getModel();
    }

    public function save(array $data, ExecuteWork $executeWork)
    {
        try {
            foreach ($data as $index => $row) {
                $executeWorkPhoto = ExecuteWorkPhoto::create([
                    'execute_work_id' => $executeWork->id,
                    'name' => 'Work on Roof ' . ($index + 1),
                    'length' => $row['length'],
                    'note' => isset($row['note']) ? $row['note'] : null,
                ]);

                foreach ($row['pictures'] as $picture) {
                    $executeWorkPhoto->addMedia($picture)->toMediaCollection('execute_work_photos');
                }

                foreach ($row['services'] as $service) {
                    $workService = WorkService::find($service['work_service_id']);
                    $warrantyTimeValue = null;
                    $warrantyTimeType = null;
                    if (isset($service['warranty_time_value'])) {
                        $warrantyTimeValue = $service['warranty_time_value'];
                        $warrantyTimeType = isset($service['warranty_time_value']) ? $service['warranty_time_value'] : null;
                    }

                    WorkWarranty::create([
                        'execute_work_photo_id' => $executeWorkPhoto->id,
                        'quantity' => 1,
                        'quantity_unit' => $workService->unit,
                        'work_service_id' => $workService->id,
                        'unit_price' => $workService->price,
                        'total_price' => $workService->price,
                        'total_paid' => 0,
                        'warranty_time_value' => $warrantyTimeValue,
                        'warranty_time_type' => $warrantyTimeType,
                    ]);
                }

                $this->setModel($executeWorkPhoto);
                $this->destroyModel();
            }
            $this->setSuccess('Successfully create execute work photo.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to create execute work photo.', $error);
        }

        return $this->getModel();
    }

    /**
     * Upload multiple photo
     *
     * @param  array  $photoDataArray
     * @return bool
     */
    public function uploadMultiplePhoto(array $photoDataArray = [])
    {
        try {
            $uploadJob = new UploadMultiplePhoto($photoDataArray);
            dispatch($uploadJob);

            $this->setSuccess('Successfully upload multiple photo.');
        } catch (Exception $e) {
            $error = $e->getMessage();
            $this->setError('Failed to upload multiple photo.', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Delete execute work photo
     *
     * @param  bool  $force
     * @return bool
     */
    public function delete(bool $force = false)
    {
        try {
            $photo = $this->getModel();
            $force ? $photo->forceDelete() : $photo->delete();
            $this->destroyModel();
            $this->setSuccess('Successfully delete execute work photo.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to delete execute work photo.', $error);
        }

        return $this->returnResponse();
    }
}
