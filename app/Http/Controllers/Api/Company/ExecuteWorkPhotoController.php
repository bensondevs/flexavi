<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\ExecuteWorkPhotos\{
    PopulateExecuteWorkPhotosRequest as PopulateRequest,
    UploadBeforeExecuteWorkPhotosRequest as UploadBeforeRequest,
    UploadAfterExecuteWorkPhotosRequest as UploadAfterRequest,
    UploadExecuteWorkPhotoRequest as UploadPhotoRequest,
    DeleteExecuteWorkPhotoRequest as DeleteRequest
};

use App\Http\Resources\ExecuteWorkPhotoResource;

use App\Repositories\ExecuteWorkPhotoRepository;

class ExecuteWorkPhotoController extends Controller
{
    /**
     * Execute Work Photo Repository Class Container
     * 
     * @var \App\Repositories\ExecuteWorkPhotoRepository
     */
    private $photo;

    /**
     * Controller constructor method
     * 
     * @param \App\Repositories\ExecuteWorkPhotoRepository  $photo
     * @return void
     */
    public function __construct(ExecuteWorkPhotoRepository $photo)
    {
        $this->photo = $photo;
    }

    /**
     * Populate execute work photos
     * 
     * @param PopulateRequest  $request
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
     * @param PopulateRequest  $request
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
     * @param UploadPhotoRequest  $request
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
     * @param UploadManyPhotosRequest  $request
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
     * @param DeleteRequest  $request
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
