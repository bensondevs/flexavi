<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\QuotationPhotos\SaveQuotationPhotoRequest as SaveRequest;
use App\Http\Requests\QuotationPhotos\FindQuotationPhotoRequest as FindRequest;
use App\Http\Requests\QuotationPhotos\PopulateQuotationPhotosRequest as PopulateRequest;

use App\Repositories\QuotationPhotoRepository;

class QuotationPhotoController extends Controller
{
    private $photo;

    public function __construct(QuotationPhotoRepository $photo)
    {
    	$this->photo = $photo;
    }

    public function quotationPhotos(SaveRequest $request)
    {
    	$options = $request->options();

    	$photos = $this->photo->all($options);
    	$photos = $this->photo->paginate();
    	$photos->data = QuotationPhotoResource::collection($photos);

    	return response()->json(['photos' => $photos]);
    }

    public function store(SaveRequest $request)
    {
    	$photoUpload = $request->file('quotation_photo');
    	$photo = $this->photo->uploadQuotationPhoto($photoUpload);

    	$input = $request->photoData();
    	$photo = $this->photo->save($input);

    	return apiResponse($this->photo, ['photo' => $photo]);
    }

    public function update(SaveRequest $request)
    {
    	$photo = $request->getQuotationPhoto();
    	$this->photo->setModel($photo);

    	if ($request->hasFile('quotation_photo')) {
    		$photoUpload = $request->file('quotation_photo');
    		$this->photo->uploadQuotationPhoto($photoUpload);
    	}

    	$input = $request->photoData();
    	$photo = $this->photo->save($input);

    	return apiResponse($this->photo, ['photo' => $photo]);
    }

    public function delete(FindRequest $request)
    {
    	$photo = $request->getQuotationPhoto();

    	$this->photo->setModel($photo);
    	$this->photo->delete();

    	return apiResponse($this->photo);
    }
}
