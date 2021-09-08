<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\ExecuteWorkPhotos\PopulateExecuteWorkPhotosRequest as PopulateRequest;
use App\Http\Requests\ExecuteWorkPhotos\UploadBeforeExecuteWorkPhotosRequest as UploadBeforeRequest;
use App\Http\Requests\ExecuteWorkPhotos\UploadAfterExecuteWorkPhotosRequest as UploadAfterRequest;
use App\Http\Requests\ExecuteWorkPhotos\UploadExecuteWorkPhotoRequest as UploadPhotoRequest;
use App\Http\Requests\ExecuteWorkPhotos\DeleteExecuteWorkPhotoRequest as DeleteRequest;

use App\Http\Resources\ExecuteWorkPhotoResource;

use App\Repositories\ExecuteWorkPhotoRepository;

class ExecuteWorkPhotoController extends Controller
{
    private $photo;

    public function __construct(ExecuteWorkPhotoRepository $photo)
    {
        $this->photo = $photo;
    }

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

    public function trashedExecuteWorkPhotos(PopulateRequest $request)
    {
        $options = $request->options();
        
        $photos = $this->photo->trasheds($options);
        $photos = ExecuteWorkPhotoResource::collection($photos);

        return response()->json(['photos' => $photos]);
    }

    public function upload(UploadPhotoRequest $request)
    {
        $input = $request->validated();
        $input['photo'] = $request->photo;

        $photo = $this->photo->uploadPhoto($input);

        return apiResponse($this->photo);
    }

    public function uploadMany(UploadManyPhotosRequest $request)
    {
        $photos = $request->photos;
        $this->photo->uploadMultiplePhoto($photos);

        return apiResponse($this->photo);
    }

    public function delete(DeleteRequest $request)
    {
        $photo = $request->getExecuteWorkPhoto();
        $this->photo->setModel($photo);

        $force = $request->input('force');
        $this->photo->delete($force);

        return apiResponse($this->photo);
    }
}
