<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\ExecuteWorkPhotos\PopulateExecuteWorkPhotosRequest as PopulateRequest;
use App\Http\Requests\ExecuteWorkPhotos\UploadBeforeExecuteWorkPhotosRequest as UploadBeforeRequest;
use App\Http\Requests\ExecuteWorkPhotos\UploadAfterExecuteWorkPhotosRequest as UploadAfterRequest;

use App\Repositories\ExecuteWorkPhotoRepository;

class ExecuteWorkPhotoController extends Controller
{
    private $photo;

    public function __construct(ExecuteWorkPhotoRepository $photo)
    {
        $this->photo = $photo;
    }

    public function executionWorkPhotos(PopulateRequest $request)
    {
        $options = $request->options();

        $photos = $this->photo->all($options);
        $beforeWorkPhotos = $this->photo->beforeWorkPhotos();
        $afterWorkPhotos = $this->photo->afterWorkPhotos();

        return response()->json([
            'before_work_photos' => $beforeWorkPhotos,
            'after_work_photos' => $afterWorkPhotos,
        ]);
    }

    public function uploadBefore(UploadBeforeRequest $request)
    {
        $photoDataArray = $request->photoDataArray();
        $this->photo->uploadMultiplePhoto($photoDataArray);

        return apiResponse($this->photo);
    }

    public function uploadAfter(UploadAfterRequest $request)
    {
        $photoDataArray = $request->photoDataArray();
        $this->photo->uploadMultiplePhoto($photoDataArray);

        return apiResponse($this->photo);
    }

    public function delete(DeleteRequest $request)
    {
        $photo = $request->getExecuteWorkPhoto();
        $this->photo->setModel($photo);
        $this->photo->delete();

        return apiResponse($this->photo);
    }
}
