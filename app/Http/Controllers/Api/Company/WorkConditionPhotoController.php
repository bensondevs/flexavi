<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\WorkConditionPhotos\FindWorkConditionPhotoRequest as FindRequest;
use App\Http\Requests\WorkConditionPhotos\SaveWorkConditionPhotoRequest as SaveRequest;
use App\Http\Requests\WorkConditionPhotos\PopulateCompanyWorkConditionPhotosRequest as PopulateRequest;

use App\Repositories\WorkConditionPhotoRepository as PhotoRepository;

class WorkConditionPhotoController extends Controller
{
    private $photo;

    public function __construct(PhotoRepository $photo)
    {
    	$this->photo = $photo;
    }

    public function companyWorkConditionPhotos()
    {
    	$photos = $this->photo->all();
    	$photos = $this->photo->paginate();
    	$photos->data = WorkConditionPhotoResource::collection($photos);

    	return response()->json(['photos' => $photos]);
    }

    public function store(SaveRequest $request)
    {
    	$photoUpload = $request->file('condition_photo');
    	$photo = $this->photo->uploadConditionPhoto($photoUpload);

    	$input = $request->photoData();
    	$photo = $this->photo->save($input);

    	return apiResponse($this->photo, ['photo' => $photo]);
    }

    public function update(SaveRequest $request)
    {
    	$photo = $request->getWorkConditionPhoto();
    	$photo = $this->photo->setModel($photo);

    	if ($request->hasFile('condition_photo')) {
    		$photoUpload = $request->file('condition_photo');
    		$photo = $this->photo->uploadConditionPhoto($photoUpload);
    	}

    	$input = $request->photoData();
    	$photo = $this->photo->save($input);

    	return apiResponse($this->photo, ['photo' => $photo]);
    }

    public function delete(FindRequest $request)
    {
    	$photo = $request->getWorkConditionPhoto();
    	$this->photo->setModel($photo);
    	$this->photo->delete();

    	return apiResponse($this->photo);
    }
}