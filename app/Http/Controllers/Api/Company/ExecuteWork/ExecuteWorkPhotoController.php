<?php

namespace App\Http\Controllers\Api\Company\ExecuteWork;

use App\Http\Controllers\Api\Company\Illuminate;
use App\Http\Controllers\Api\Company\UploadManyPhotosRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\ExecuteWorkPhotos\{DeleteExecuteWorkPhotoRequest as DeleteRequest};
use App\Http\Requests\Company\ExecuteWorkPhotos\PopulateExecuteWorkPhotosRequest as PopulateRequest;
use App\Http\Requests\Company\ExecuteWorkPhotos\UploadExecuteWorkPhotoRequest as UploadPhotoRequest;
use App\Http\Resources\ExecuteWork\ExecuteWorkPhotoResource;
use App\Repositories\ExecuteWork\ExecuteWorkPhotoRepository;

class ExecuteWorkPhotoController extends Controller
{
    /**
     * Execute Work Photo Repository Class Container
     *
     * @var ExecuteWorkPhotoRepository
     */
    private $photo;

    /**
     * Controller constructor method
     *
     * @param ExecuteWorkPhotoRepository $photo
     * @return void
     */
    public function __construct(ExecuteWorkPhotoRepository $photo)
    {
        $this->photo = $photo;
    }

    /**
     * Populate execute work photos
     *
     * @param PopulateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function executeWorkPhotos(PopulateRequest $request)
    {
        $options = $request->options();

        $photos = $this->photo->all($options);

        $beforeWorkPhotos = $this->photo->beforeWorkPhotos();
        $beforeWorkPhotos = ExecuteWorkPhotoResource::collection($beforeWorkPhotos);

        $afterWorkPhotos = $this->photo->afterWorkPhotos();
        $afterWorkPhotos = ExecuteWorkPhotoResource::collection($afterWorkPhotos);

        return response()->json([
            'before_work_photos' => $beforeWorkPhotos,
            'after_work_photos' => $afterWorkPhotos,
        ]);
    }

    /**
     * Populate trashed execute work photos
     *
     * @param PopulateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function trashedExecuteWorkPhotos(PopulateRequest $request)
    {
        $options = $request->options();

        $photos = $this->photo->trasheds($options);
        $photos = ExecuteWorkPhotoResource::collection($photos);

        return response()->json(['photos' => $photos]);
    }

    /**
     * Upload execute work photo
     *
     * @param UploadPhotoRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function upload(UploadPhotoRequest $request)
    {
        $input = $request->validated();
        $input['photo'] = $request->photo;

        $photo = $this->photo->uploadPhoto($input);

        return apiResponse($this->photo);
    }

    /**
     * Upload many photo for execute work log
     *
     * @param UploadManyPhotosRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function uploadMany(UploadManyPhotosRequest $request)
    {
        $photos = $request->photos;
        $this->photo->uploadMultiplePhoto($photos);

        return apiResponse($this->photo);
    }

    /**
     * Delete execute work photo
     *
     * @param DeleteRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function delete(DeleteRequest $request)
    {
        $photo = $request->getExecuteWorkPhoto();
        $this->photo->setModel($photo);

        $force = $request->input('force');
        $this->photo->delete($force);

        return apiResponse($this->photo);
    }
}
